<script>
(function () {
    if (window.initializeRepairAutocomplete) {
        window.initializeRepairAutocomplete();
        return;
    }

    const supportsAbortController = typeof window !== 'undefined' && typeof window.AbortController === 'function';
    const localDataCache = new Map();

    const loadLocalData = (selector) => {
        if (!selector || typeof selector !== 'string') {
            return null;
        }

        if (localDataCache.has(selector)) {
            return localDataCache.get(selector);
        }

        let parsed = [];

        try {
            const element = document.querySelector(selector);

            if (!element) {
                localDataCache.set(selector, []);
                return [];
            }

            const sourceText = (element.textContent ?? element.value ?? '').trim();

            if (!sourceText) {
                parsed = [];
            } else {
                const raw = JSON.parse(sourceText);
                parsed = Array.isArray(raw) ? raw : [];
            }
        } catch (parseError) {
            console.error('Autocomplete local data parse failed', parseError);
            parsed = [];
        }

        localDataCache.set(selector, parsed);
        return parsed;
    };

    const debounce = (fn, delay = 250) => {
        let timerId;
        const debounced = (...args) => {
            window.clearTimeout(timerId);
            timerId = window.setTimeout(() => fn.apply(null, args), delay);
        };
        debounced.flush = (...args) => {
            window.clearTimeout(timerId);
            fn.apply(null, args);
        };
        return debounced;
    };

    const toInteger = (value, fallback) => {
        const parsed = Number.parseInt(value, 10);
        return Number.isFinite(parsed) ? parsed : fallback;
    };

    const buildLabel = (item, options) => {
        const { labelKey, valueKey, secondaryKey, showId } = options;
        const labelValue = item && Object.prototype.hasOwnProperty.call(item, labelKey)
            ? item[labelKey]
            : '';
        const idValue = item && Object.prototype.hasOwnProperty.call(item, valueKey)
            ? item[valueKey]
            : '';
        const secondaryValue = secondaryKey && item && Object.prototype.hasOwnProperty.call(item, secondaryKey)
            ? item[secondaryKey]
            : '';

        let labelText = labelValue === undefined || labelValue === null ? '' : String(labelValue);
        const idText = idValue === undefined || idValue === null ? '' : String(idValue);
        const secondaryText = secondaryValue === undefined || secondaryValue === null || secondaryValue === ''
            ? ''
            : String(secondaryValue);

        if (showId && idText) {
            labelText = labelText ? `#${idText} - ${labelText}` : `#${idText}`;
        } else if (!labelText && idText) {
            labelText = idText;
        }

        if (secondaryText) {
            labelText = labelText ? `${labelText} (${secondaryText})` : `(${secondaryText})`;
        }

        return labelText;
    };

    const createRequestUrl = (baseUrl, query) => {
        const trimmedBase = typeof baseUrl === 'string' ? baseUrl.trim() : '';

        if (!trimmedBase) {
            return '';
        }

        try {
            let url;

            if (/^https?:\/\//i.test(trimmedBase)) {
                const parsed = new URL(trimmedBase);
                const relativePath = `${parsed.pathname}${parsed.search ?? ''}`;
                url = new URL(relativePath || '/', window.location.origin);
            } else {
                url = new URL(trimmedBase, window.location.origin);
            }

            url.searchParams.set('q', query);
            return url.toString();
        } catch (error) {
            const withoutOrigin = trimmedBase.replace(/^https?:\/\/[^/]+/i, '');
            const normalizedBase = withoutOrigin.startsWith('/') ? withoutOrigin : `/${withoutOrigin}`;
            const separator = normalizedBase.includes('?') ? '&' : '?';
            return `${window.location.origin}${normalizedBase}${separator}q=${encodeURIComponent(query)}`;
        }
    };

    const dispatchEventSafe = (eventName) => {
        if (typeof document === 'undefined' || !eventName) {
            return;
        }

        try {
            document.dispatchEvent(new Event(eventName));
        } catch (dispatchError) {
            if (typeof document.createEvent === 'function') {
                const fallbackEvent = document.createEvent('Event');
                fallbackEvent.initEvent(eventName, true, true);
                document.dispatchEvent(fallbackEvent);
            }
        }
    };

    const initializeContainer = (container) => {
        const input = container.querySelector('[data-autocomplete-input]');
        if (!input) {
            return;
        }

        const resultsList = container.querySelector('[data-autocomplete-results]');
        const url = input.getAttribute('data-autocomplete-url');
        const localDataSelector = input.getAttribute('data-autocomplete-local-data');
        const prefersLocalOnly = input.getAttribute('data-autocomplete-local-only') === 'true';
        let localData = localDataSelector ? loadLocalData(localDataSelector) : null;
        localData = Array.isArray(localData) ? localData : null;

        if (!url && !localData) {
            return;
        }

        const hiddenSelector = input.getAttribute('data-autocomplete-hidden-input');
        let hiddenInput = null;
        if (hiddenSelector) {
            hiddenInput = container.querySelector(hiddenSelector) || document.querySelector(hiddenSelector);
        }
        if (!hiddenInput) {
            hiddenInput = container.querySelector('input[type="hidden"][name]');
        }

        const labelKey = input.getAttribute('data-autocomplete-label-key') || 'name';
        const valueKey = input.getAttribute('data-autocomplete-value-key') || 'id';
        const secondaryKey = input.getAttribute('data-autocomplete-secondary-key');
        const showId = input.getAttribute('data-autocomplete-show-id') === 'true';
        const maxResults = toInteger(input.getAttribute('data-autocomplete-max-results'), 10);
        const minLength = toInteger(input.getAttribute('data-autocomplete-min-length'), 1);
        const delay = toInteger(input.getAttribute('data-autocomplete-delay'), 250);
        const remoteMinLengthAttr = input.getAttribute('data-autocomplete-remote-min-length');
        const remoteMinLength = Math.max(0, toInteger(remoteMinLengthAttr, Math.max(minLength, 1)));
        const emptyText = input.getAttribute('data-autocomplete-empty-text') || '';

        const options = { labelKey, valueKey, secondaryKey, showId };

        const filterLocalResults = (query) => {
            if (!Array.isArray(localData)) {
                return [];
            }

            const normalizedQuery = typeof query === 'string' ? query.trim().toLowerCase() : '';
            const queryParts = normalizedQuery ? normalizedQuery.split(/\s+/).filter(Boolean) : [];

            return localData.filter((item) => {
                if (!queryParts.length) {
                    return true;
                }

                const labelText = buildLabel(item, options).toLowerCase();
                const valueText = item && Object.prototype.hasOwnProperty.call(item, valueKey)
                    ? String(item[valueKey] ?? '').toLowerCase()
                    : '';
                const secondaryText = secondaryKey && item && Object.prototype.hasOwnProperty.call(item, secondaryKey)
                    ? String(item[secondaryKey] ?? '').toLowerCase()
                    : '';
                const extraText = item && typeof item.search_terms === 'string'
                    ? item.search_terms.toLowerCase()
                    : '';

                return queryParts.every((part) => (
                    (labelText && labelText.includes(part))
                    || (valueText && valueText.includes(part))
                    || (secondaryText && secondaryText.includes(part))
                    || (extraText && extraText.includes(part))
                ));
            });
        };

        let activeIndex = -1;
        let items = [];
        let controller = null;
        let requestToken = 0;

        const abortActiveRequest = () => {
            if (controller && typeof controller.abort === 'function') {
                controller.abort();
            }

            controller = null;
        };

        const clearResults = () => {
            items = [];
            activeIndex = -1;
            if (resultsList) {
                resultsList.innerHTML = '';
                resultsList.hidden = true;
                resultsList.classList.remove('show');
            }
        };

        const setActiveIndex = (index) => {
            if (!resultsList) {
                return;
            }

            const children = Array.from(resultsList.children);
            children.forEach((child, childIndex) => {
                if (childIndex === index) {
                    child.classList.add('active');
                    child.setAttribute('aria-selected', 'true');
                } else {
                    child.classList.remove('active');
                    child.removeAttribute('aria-selected');
                }
            });
            activeIndex = index;
        };

        const selectItem = (item) => {
            if (!item) {
                return;
            }

            const value = item[valueKey];
            const display = buildLabel(item, options);

            if (hiddenInput) {
                hiddenInput.value = value ?? '';
                hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));
            }

            input.value = display;
            input.dataset.autocompleteSelectedValue = value ?? '';
            input.dataset.autocompleteSelectedLabel = display;
            input.dispatchEvent(new Event('change', { bubbles: true }));

            clearResults();

            const detail = { item, value, label: display, input };
            input.dispatchEvent(new CustomEvent('repairs:autocomplete-selected', { detail }));
        };

        const renderResults = (data) => {
            if (!resultsList) {
                return;
            }

            const limitedItems = Array.isArray(data) ? data.slice(0, maxResults) : [];
            items = limitedItems;
            resultsList.innerHTML = '';
            activeIndex = -1;

            if (!limitedItems.length) {
                if (emptyText) {
                    const emptyItem = document.createElement('div');
                    emptyItem.className = 'list-group-item disabled';
                    emptyItem.textContent = emptyText;
                    resultsList.appendChild(emptyItem);
                    resultsList.hidden = false;
                    resultsList.classList.add('show');
                } else {
                    clearResults();
                }
                return;
            }

            limitedItems.forEach((item, index) => {
                const button = document.createElement('button');
                button.type = 'button';
                button.className = 'list-group-item list-group-item-action';
                button.textContent = buildLabel(item, options);
                button.setAttribute('data-autocomplete-index', String(index));
                button.addEventListener('mousedown', (event) => {
                    event.preventDefault();
                    selectItem(item);
                });
                button.addEventListener('mouseenter', () => setActiveIndex(index));
                resultsList.appendChild(button);
            });

            resultsList.hidden = false;
            resultsList.classList.add('show');
        };

        const performSearch = () => {
            const query = input.value.trim();

            if (localDataSelector) {
                localData = loadLocalData(localDataSelector);
                localData = Array.isArray(localData) ? localData : null;
            }

            const localResults = filterLocalResults(query);
            const hasLocalData = Array.isArray(localData) && localData.length > 0;
            const enforceLocalOnly = prefersLocalOnly && hasLocalData;
            const isShortQuery = query.length < minLength;
            const belowRemoteThreshold = query.length < remoteMinLength;

            if (isShortQuery) {
                requestToken += 1;
                abortActiveRequest();

                if (hasLocalData) {
                    renderResults(localResults);
                } else {
                    clearResults();
                }
                return;
            }

            if (hasLocalData) {
                renderResults(localResults);
            }

            if (enforceLocalOnly && localResults.length) {
                return;
            }

            if (belowRemoteThreshold) {
                if (!hasLocalData) {
                    clearResults();
                }
                return;
            }

            if (!url) {
                if (!hasLocalData) {
                    clearResults();
                }
                return;
            }

            const currentToken = ++requestToken;

            abortActiveRequest();

            controller = null;
            if (supportsAbortController) {
                try {
                    controller = new window.AbortController();
                } catch (controllerError) {
                    controller = null;
                }
            }

            const requestUrl = createRequestUrl(url, query);

            if (!requestUrl) {
                if (!hasLocalData) {
                    clearResults();
                }
                return;
            }

            const fetchOptions = {
                credentials: 'include',
                headers: {
                    'Accept': 'application/json, text/plain, */*',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            };

            if (controller && controller.signal) {
                fetchOptions.signal = controller.signal;
            }

            const activeController = controller;

            fetch(requestUrl, fetchOptions)
                .then(async (response) => {
                    if (!response.ok) {
                        return [];
                    }
                    try {
                        return await response.clone().json();
                    } catch (parseError) {
                        try {
                            const text = await response.text();
                            return JSON.parse(text);
                        } catch (fallbackError) {
                            console.error('Autocomplete response parse failed', fallbackError);
                            return [];
                        }
                    }
                })
                .then((data) => {
                    if (currentToken !== requestToken) {
                        return;
                    }

                    if (!Array.isArray(data)) {
                        if (!hasLocalData) {
                            renderResults([]);
                        }
                        return;
                    }

                    renderResults(data);
                })
                .catch((error) => {
                    if (error && error.name === 'AbortError') {
                        return;
                    }

                    if (currentToken === requestToken) {
                        console.error('Autocomplete request failed', error);
                        if (hasLocalData) {
                            renderResults(localResults);
                        } else {
                            clearResults();
                        }
                    }
                })
                .finally(() => {
                    if (controller === activeController) {
                        controller = null;
                    }
                });
        };

        const debouncedSearch = debounce(performSearch, delay);

        input.addEventListener('input', () => {
            if (hiddenInput && input.value.trim() === '') {
                hiddenInput.value = '';
                hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));
            }

            debouncedSearch();
        });

        input.addEventListener('focus', () => {
            if (input.value.trim().length >= minLength) {
                debouncedSearch();
            }
        });

        input.addEventListener('keydown', (event) => {
            if (!items.length) {
                return;
            }

            if (event.key === 'ArrowDown') {
                event.preventDefault();
                const nextIndex = activeIndex + 1 >= items.length ? 0 : activeIndex + 1;
                setActiveIndex(nextIndex);
            } else if (event.key === 'ArrowUp') {
                event.preventDefault();
                const previousIndex = activeIndex - 1 < 0 ? items.length - 1 : activeIndex - 1;
                setActiveIndex(previousIndex);
            } else if (event.key === 'Enter') {
                if (activeIndex >= 0 && activeIndex < items.length) {
                    event.preventDefault();
                    selectItem(items[activeIndex]);
                }
            } else if (event.key === 'Escape') {
                clearResults();
            }
        });

        input.addEventListener('blur', () => {
            window.setTimeout(() => {
                clearResults();
            }, 150);
        });

        container.dataset.autocompleteInitialized = 'true';
    };

    window.initializeRepairAutocomplete = () => {
        document.querySelectorAll('[data-autocomplete-container]').forEach((container) => {
            if (container.dataset.autocompleteInitialized === 'true') {
                return;
            }
            initializeContainer(container);
        });
    };

    const runInitialAutocomplete = () => {
        window.initializeRepairAutocomplete();
        dispatchEventSafe('repairs:autocomplete-ready');
    };

    if (document.readyState === 'loading') {
        const handleDOMContentLoaded = () => {
            document.removeEventListener('DOMContentLoaded', handleDOMContentLoaded);
            runInitialAutocomplete();
        };

        document.addEventListener('DOMContentLoaded', handleDOMContentLoaded);
    } else {
        runInitialAutocomplete();
    }

    const handleRefreshAutocomplete = () => {
        localDataCache.clear();
        window.initializeRepairAutocomplete();
    };

    document.addEventListener('repairs:refresh-autocomplete', handleRefreshAutocomplete);
})();
</script>
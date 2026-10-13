<script>
(function () {
    if (window.initializeQuickIssueModal) {
        return;
    }

    const validationErrorMessage = @json(__('validation.required', ['attribute' => strtolower(__('locale.lbl_issue'))]));
    const clientValidationMessage = @json(__('validation.required', ['attribute' => strtolower(__('locale.lbl_client'))]));
    const generalErrorMessage = @json(__('locale.lbl_error_input'));
    const successMessageFallback = @json(__('locale.created_successfully'));

    let currentRepairForm = null;

    const storeBodyPadding = (modalEl) => {
        if (!modalEl || typeof document === 'undefined') {
            return;
        }

        try {
            modalEl.dataset.quickIssueBodyPadding = document.body.style.paddingRight || '';
        } catch (error) {
            modalEl.dataset.quickIssueBodyPadding = '';
        }
    };

    const restoreUnderlyingModalState = (modalEl) => {
        if (!modalEl || typeof document === 'undefined') {
            return;
        }

        const openModals = Array.from(document.querySelectorAll('.modal.show')).filter((modal) => modal !== modalEl);
        if (!openModals.length) {
            delete modalEl.dataset.quickIssueBodyPadding;
            return;
        }

        document.body.classList.add('modal-open');

        if (Object.prototype.hasOwnProperty.call(modalEl.dataset, 'quickIssueBodyPadding')) {
            document.body.style.paddingRight = modalEl.dataset.quickIssueBodyPadding;
        }

        const existingBackdrop = document.querySelector('.modal-backdrop');
        if (existingBackdrop) {
            existingBackdrop.classList.add('show');
            if (existingBackdrop.style.display === 'none') {
                existingBackdrop.style.display = '';
            }
            return;
        }

        const [targetModal] = openModals;
        const modalConstructor = window.bootstrap && window.bootstrap.Modal;
        const modalInstance = modalConstructor ? modalConstructor.getInstance(targetModal) : null;

        if (modalInstance && modalInstance._backdrop && typeof modalInstance._backdrop.show === 'function') {
            modalInstance._backdrop.show();
            return;
        }

        const backdrop = document.createElement('div');
        backdrop.className = 'modal-backdrop fade show';
        backdrop.dataset.quickIssueRestored = 'true';
        document.body.appendChild(backdrop);

        if (targetModal) {
            const handleTargetHidden = () => {
                backdrop.remove();
            };

            targetModal.addEventListener('hidden.bs.modal', handleTargetHidden, { once: true });
        }
    };

    const setupClientField = (modalEl) => {
        if (!modalEl) {
            return { ensure: () => true, reset: () => {}, input: null };
        }

        const container = modalEl.querySelector('[data-quick-issue-client]');
        if (!container) {
            return { ensure: () => true, reset: () => {}, input: null };
        }

        const searchInput = container.querySelector('[data-quick-issue-client-search]');
        const hiddenInput = container.querySelector('[data-quick-issue-client-id]');

        if (!searchInput || !hiddenInput) {
            return { ensure: () => true, reset: () => {}, input: null };
        }

        const validationMessage = searchInput.getAttribute('data-validation-message') || clientValidationMessage;

        const ensureSelection = () => {
            const hasValue = hiddenInput.value.trim() !== '';
            searchInput.setCustomValidity(hasValue ? '' : validationMessage);
            return hasValue;
        };

        const resetSelection = () => {
            searchInput.setCustomValidity('');
        };

        if (searchInput.dataset.quickIssueClientInitialized !== 'true') {
            searchInput.addEventListener('input', () => {
                if (hiddenInput.value !== '') {
                    hiddenInput.value = '';
                    hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));
                }
                searchInput.setCustomValidity('');
            });
            searchInput.dataset.quickIssueClientInitialized = 'true';
        }

        if (hiddenInput.dataset.quickIssueClientInitialized !== 'true') {
            hiddenInput.addEventListener('change', () => {
                searchInput.setCustomValidity('');
            });
            hiddenInput.dataset.quickIssueClientInitialized = 'true';
        }

        resetSelection();

        return { ensure: ensureSelection, reset: resetSelection, input: searchInput };
    };

    const ensureSingleModal = () => {
        const modalElements = Array.from(document.querySelectorAll('#quickIssueModal'));
        if (!modalElements.length) {
            return null;
        }

        const activeModal = modalElements.pop();
        modalElements.forEach((duplicate) => duplicate.remove());

        if (!document.body.contains(activeModal)) {
            document.body.appendChild(activeModal);
        }

        return activeModal;
    };

    const resetMessages = (modalEl) => {
        const errorBox = modalEl.querySelector('[data-quick-issue-errors]');
        const successBox = modalEl.querySelector('[data-quick-issue-success]');

        if (errorBox) {
            errorBox.classList.add('d-none');
            errorBox.innerHTML = '';
        }

        if (successBox) {
            successBox.classList.add('d-none');
            successBox.textContent = '';
        }
    };

    const displayErrors = (modalEl, errors) => {
        const errorBox = modalEl.querySelector('[data-quick-issue-errors]');
        if (!errorBox) {
            return;
        }

        const messages = [];
        if (errors && typeof errors === 'object') {
            Object.values(errors).forEach((value) => {
                if (Array.isArray(value)) {
                    value.forEach((item) => {
                        if (item) {
                            messages.push(String(item));
                        }
                    });
                } else if (value) {
                    messages.push(String(value));
                }
            });
        }

        if (!messages.length) {
            messages.push(generalErrorMessage);
        }

        errorBox.innerHTML = '<ul class="mb-0"><li>' + messages.map((message) => message.replace(/</g, '&lt;').replace(/>/g, '&gt;')).join('</li><li>') + '</li></ul>';
        errorBox.classList.remove('d-none');
    };

    const displaySuccess = (modalEl, message) => {
        const successBox = modalEl.querySelector('[data-quick-issue-success]');
        if (!successBox) {
            return;
        }

        successBox.textContent = message || successMessageFallback;
        successBox.classList.remove('d-none');
    };

    const updateInternalNotes = (form) => {
        const descriptionField = form.querySelector('[data-quick-issue-description]');
        const internalNotesField = form.querySelector('[data-quick-issue-internal-notes]');

        if (descriptionField && internalNotesField) {
            internalNotesField.value = descriptionField.value.trim();
        }
    };

    const updateRepairFormSelection = (form, issue, message) => {
        const targetForm = form || document.querySelector('[data-repair-form]');
        if (!targetForm) {
            return;
        }

        const hiddenInput = targetForm.querySelector('#repair_issue_id');
        const searchInput = targetForm.querySelector('#repair_issue_search');
        const errorContainer = targetForm.querySelector('[data-issue-required-error]');
        const successContainer = targetForm.querySelector('[data-quick-issue-success-message]');

        if (hiddenInput) {
            hiddenInput.value = issue?.id ? String(issue.id) : '';
            hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));
        }

        if (searchInput) {
            const labelParts = [];
            if (issue?.id) {
                labelParts.push(`#${issue.id}`);
            }
            if (issue?.subject) {
                labelParts.push(issue.subject);
            }
            let label = labelParts.join(' - ');
            if (issue?.client) {
                label = label ? `${label} (${issue.client})` : issue.client;
            }
            searchInput.value = label;
            searchInput.dispatchEvent(new Event('input', { bubbles: true }));
            searchInput.focus();
        }

        if (errorContainer) {
            errorContainer.textContent = '';
            errorContainer.classList.add('d-none');
        }

        if (successContainer) {
            successContainer.textContent = message || successMessageFallback;
            successContainer.classList.remove('d-none');
        }
    };

    const attachRepairFormValidation = (context = document) => {
        const forms = context.querySelectorAll('[data-repair-form]');
        forms.forEach((form) => {
            if (form.dataset.quickIssueValidationInitialized === 'true') {
                return;
            }

            const hiddenInput = form.querySelector('#repair_issue_id');
            const searchInput = form.querySelector('#repair_issue_search');
            const errorContainer = form.querySelector('[data-issue-required-error]');
            const successContainer = form.querySelector('[data-quick-issue-success-message]');

            form.addEventListener('submit', (event) => {
                if (hiddenInput && hiddenInput.value.trim() !== '') {
                    return;
                }

                event.preventDefault();
                if (errorContainer) {
                    errorContainer.textContent = errorContainer.dataset.errorMessage || validationErrorMessage;
                    errorContainer.classList.remove('d-none');
                }

                if (successContainer) {
                    successContainer.classList.add('d-none');
                    successContainer.textContent = '';
                }

                if (searchInput) {
                    searchInput.focus();
                }
            });

            if (hiddenInput) {
                hiddenInput.addEventListener('change', () => {
                    if (hiddenInput.value.trim() !== '' && errorContainer) {
                        errorContainer.textContent = '';
                        errorContainer.classList.add('d-none');
                    }
                });
            }

            if (searchInput) {
                searchInput.addEventListener('input', () => {
                    if (hiddenInput) {
                        hiddenInput.value = '';
                    }
                    if (successContainer) {
                        successContainer.classList.add('d-none');
                        successContainer.textContent = '';
                    }
                });
            }

            form.dataset.quickIssueValidationInitialized = 'true';
        });
    };

    const initializeQuickIssueModal = (context = document) => {
        const modalEl = ensureSingleModal();
        if (!modalEl) {
            return;
        }

        const modalConstructor = window.bootstrap && window.bootstrap.Modal;
        if (!modalConstructor) {
            return;
        }

        const modalInstance = modalConstructor.getOrCreateInstance(modalEl);
        const form = modalEl.querySelector('[data-quick-issue-form]');
        const openButtons = context.querySelectorAll('[data-quick-issue-open]');
        const clientField = setupClientField(modalEl);

        if (typeof window.initIssueCategoryDropdown === 'function') {
            window.initIssueCategoryDropdown(modalEl);
        }

        openButtons.forEach((button) => {
            if (button.dataset.quickIssueOpenInitialized === 'true') {
                return;
            }

            button.addEventListener('click', () => {
                currentRepairForm = button.closest('[data-repair-form]') || document.querySelector('[data-repair-form]');
                if (form) {
                    form.reset();
                    form.classList.remove('was-validated');
                    if (typeof window.initIssueCategoryDropdown === 'function') {
                        window.initIssueCategoryDropdown(modalEl);
                    }
                }
                if (clientField && typeof clientField.reset === 'function') {
                    clientField.reset();
                }
                resetMessages(modalEl);
                modalInstance.show();
            });

            button.dataset.quickIssueOpenInitialized = 'true';
        });

        if (form && !form.dataset.quickIssueInitialized) {
            form.addEventListener('submit', async (event) => {
                event.preventDefault();
                form.classList.remove('was-validated');
                resetMessages(modalEl);

                if (clientField && typeof clientField.ensure === 'function') {
                    clientField.ensure();
                }

                if (!form.checkValidity()) {
                    form.classList.add('was-validated');
                    return;
                }

                updateInternalNotes(form);

                const action = form.getAttribute('action');
                const formData = new FormData(form);

                try {
                    const response = await fetch(action, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: formData,
                    });

                    const data = await response.clone().json().catch(() => null);

                    if (!response.ok) {
                        displayErrors(modalEl, data?.errors || data);
                        return;
                    }

                    displaySuccess(modalEl, data?.message);
                    updateRepairFormSelection(currentRepairForm, data?.issue, data?.message);
                    modalInstance.hide();
                } catch (error) {
                    displayErrors(modalEl, null);
                }
            });

            form.dataset.quickIssueInitialized = 'true';
        }
        if (modalEl && modalEl.dataset.quickIssueShowInitialized !== 'true') {
            modalEl.addEventListener('show.bs.modal', () => {
                storeBodyPadding(modalEl);
                if (clientField && typeof clientField.reset === 'function') {
                    clientField.reset();
                }
            });

            modalEl.dataset.quickIssueShowInitialized = 'true';
        }

        if (modalEl && modalEl.dataset.quickIssueFocusInitialized !== 'true') {
            modalEl.addEventListener('shown.bs.modal', () => {
                if (clientField && clientField.input) {
                    clientField.input.focus();
                }
            });

            modalEl.dataset.quickIssueFocusInitialized = 'true';
        }


        if (modalEl && !modalEl.dataset.quickIssueResetInitialized) {
            modalEl.addEventListener('hidden.bs.modal', () => {
                if (form) {
                    form.reset();
                    form.classList.remove('was-validated');
                    if (typeof window.initIssueCategoryDropdown === 'function') {
                        window.initIssueCategoryDropdown(modalEl);
                    }
                }
                resetMessages(modalEl);
                if (clientField && typeof clientField.reset === 'function') {
                    clientField.reset();
                }
                currentRepairForm = null;
                restoreUnderlyingModalState(modalEl);
            });

            modalEl.dataset.quickIssueResetInitialized = 'true';
        }

        attachRepairFormValidation(context);
    };

    window.initializeQuickIssueModal = function (context = document) {
        attachRepairFormValidation(context);
        initializeQuickIssueModal(context);
    };

    const bootstrapQuickIssue = () => {
        window.initializeQuickIssueModal(document);
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', bootstrapQuickIssue);
    } else {
        bootstrapQuickIssue();
    }

    document.addEventListener('repairs:refresh-quick-issue', () => {
        window.initializeQuickIssueModal(document);
    });
})();
</script>
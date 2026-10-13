<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof $ === 'undefined' || !$.fn.select2) {
            return;
        }

        const clientSelects = $('.js-vp-client-select');
        const assignedUserSelects = $('.js-assigned-users-select');

        assignedUserSelects.each(function () {
            const $select = $(this);
            const placeholder = $select.data('placeholder') || '';

            $select.select2({
                allowClear: true,
                width: '100%',
                placeholder: placeholder,
                minimumResultsForSearch: 0
            });
        });

        clientSelects.each(function () {
            const $select = $(this);
            const ajaxUrl = $select.data('ajax-url');
            const placeholder = $select.data('placeholder') || '';

            if (!ajaxUrl) {
                return;
            }

            $select.select2({
                allowClear: true,
                width: '100%',
                placeholder: placeholder,
                minimumInputLength: 2,
                ajax: {
                    url: ajaxUrl,
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term || ''
                        };
                    },
                    processResults: function (data) {
                        const results = data.map(function (client) {
                            let label = client.raison_sociale;
                            if (client.ville) {
                                label += ' (' + client.ville + ')';
                            }

                            return {
                                id: client.id,
                                text: label
                            };
                        });

                        const limitedResults = results.slice(0, 15);

                        return { results: limitedResults };
                    }
                }
            });
        });
    });
</script>
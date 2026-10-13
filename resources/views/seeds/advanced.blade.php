@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-4">
            <div>
                <h4 class="mb-1">{{ __('Advanced Seeding') }}</h4>
                <p class="text-muted mb-0">{{ __('Generate demo tickets for specific clients and users.') }}</p>
            </div>
            <a href="{{ route('seeds.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> {{ __('Back to Dashboard') }}
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <form action="{{ route('seeds.advanced.run') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label font-weight-bold" for="client_ids">{{ __('1. Select Clients') }}</label>
                            <select id="client_ids" name="client_ids[]" class="select2 form-select" multiple required>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" {{ in_array((string)$client->id, old('client_ids', [])) || in_array($client->id, old('client_ids', [])) ? 'selected' : '' }}>
                                        {{ $client->raison_sociale }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">{{ __('Data will be randomized across these clients.') }}</small>
                        </div>
                        
                        <div class="col-md-12 mt-4">
                            <label class="form-label font-weight-bold" for="user_ids">{{ __('2. Select Users') }}</label>
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <select id="user_ids" name="user_ids[]" class="select2 form-select" multiple required disabled>
                                    <option value="">{{ __('Select clients first') }}</option>
                                </select>
                                <div id="users-loading" class="spinner-border spinner-border-sm text-primary d-none" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                            <small class="text-muted">{{ __('Only users assigned to the selected clients are shown.') }}</small>
                        </div>

                        <div class="col-md-6 mt-4">
                            <label class="form-label font-weight-bold" for="count">{{ __('3. Number of tickets') }}</label>
                            <input type="number" id="count" name="count" class="form-control" value="{{ old('count', 10) }}" min="1" max="500" required>
                        </div>

                        <div class="col-12 mt-5">
                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                <i class="bi bi-play-circle me-1"></i> {{ __('Execute Seeding') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Initialize Select2
        if (typeof $.fn.select2 !== 'undefined') {
            $('.select2').select2({
                placeholder: "{{ __('Search...') }}",
                allowClear: true,
                width: '100%'
            });
        }

        let oldUserIds = @json(old('user_ids', []));
        oldUserIds = oldUserIds.map(String);

        $('#client_ids').on('change', function() {
            const clientIds = $(this).val();
            const userSelect = $('#user_ids');
            const loading = $('#users-loading');

            if (!clientIds || clientIds.length === 0) {
                userSelect.empty().trigger('change').prop('disabled', true);
                return;
            }

            loading.removeClass('d-none');
            
            $.ajax({
                url: "{{ route('seeds.advanced.users') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    client_ids: clientIds
                },
                success: function(users) {
                    userSelect.empty();
                    if (users.length > 0) {
                        users.forEach(user => {
                            const isSelected = oldUserIds.includes(String(user.id));
                            const option = new Option(user.name, user.id, false, isSelected);
                            userSelect.append(option);
                        });
                        userSelect.prop('disabled', false);
                        // Clear old user IDs after first load so it doesn't apply to subsequent changes
                        oldUserIds = [];
                    } else {
                        userSelect.prop('disabled', true);
                    }
                    userSelect.trigger('change');
                },
                error: function() {
                    console.error("Failed to fetch users");
                },
                complete: function() {
                    loading.addClass('d-none');
                }
            });
        });

        // Trigger change on load if clients are pre-selected
        if ($('#client_ids').val() && $('#client_ids').val().length > 0) {
            $('#client_ids').trigger('change');
        }
    });
</script>

<style>
    .font-weight-bold { font-weight: 600; }
    .select2-container--default .select2-selection--multiple {
        border-color: #dbdade;
    }
    .select2-container--default.select2-container--focus .select2-selection--multiple {
        border-color: #91378e;
    }
</style>
@endsection

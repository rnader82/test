<div class="mb-3">
    <label for="client_id" class="form-label">Client</label>
    <select name="client_id" id="client_id" class="form-select select2 @error('client_id') is-invalid @enderror" required>
        <option value="">Sélectionner un client</option>
        @foreach($clients as $client)
            <option value="{{ $client->id }}" {{ (old('client_id', $project->client_id ?? '') == $client->id) ? 'selected' : '' }}>
                {{ $client->raison_sociale }}{{ $client->ville ? ' - ' . $client->ville : '' }}
            </option>
        @endforeach
    </select>
    @error('client_id')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="name" class="form-label">Nom du Projet</label>
    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $project->name ?? '') }}" required>
    @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="date_debut" class="form-label">Date Début</label>
        <input type="date" name="date_debut" id="date_debut" class="form-control @error('date_debut') is-invalid @enderror" value="{{ old('date_debut', isset($project) && $project->date_debut ? $project->date_debut->format('Y-m-d') : '') }}">
        @error('date_debut')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label for="date_fin" class="form-label">Date Fin</label>
        <input type="date" name="date_fin" id="date_fin" class="form-control @error('date_fin') is-invalid @enderror" value="{{ old('date_fin', isset($project) && $project->date_fin ? $project->date_fin->format('Y-m-d') : '') }}">
        @error('date_fin')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="card mt-4 mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Équipements nécessaires</h5>
        <button type="button" class="btn btn-sm btn-outline-primary" id="add-equipment">
            <i class="bi bi-plus-lg me-1"></i> Ajouter un équipement
        </button>
    </div>
    <div class="card-body">
        <div id="equipment-container">
            @if(isset($project) && $project->equipments->count())
                @foreach($project->equipments as $index => $equip)
                    <div class="row equipment-row mb-3 align-items-end" id="eq-row-{{ $index }}">
                        <div class="col-md-5">
                            <label class="form-label">Équipement</label>
                            <select name="equipments[{{ $index }}][id]" class="form-select select2-equipment" required>
                                <option value="{{ $equip->id }}" selected>{{ $equip->name }} ({{ $equip->reference }})</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Quantité</label>
                            <input type="number" name="equipments[{{ $index }}][quantity]" class="form-control" value="{{ $equip->pivot->quantity }}" min="1" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Date besoin</label>
                            <input type="date" name="equipments[{{ $index }}][needed_by_date]" class="form-control" value="{{ $equip->pivot->needed_by_date ? \Carbon\Carbon::parse($equip->pivot->needed_by_date)->format('Y-m-d') : '' }}">
                        </div>
                        <div class="col-md-2 text-end">
                            <button type="button" class="btn btn-outline-danger remove-equipment" data-row="eq-row-{{ $index }}">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Initializer select2 principal (Client)
        $('.select2').select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: 'Sélectionner un client',
            allowClear: true
        });

        let eqIndex = {{ isset($project) ? $project->equipments->count() : 0 }};
        
        function initEquipmentSelect2(element) {
            element.select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: 'Rechercher un équipement...',
                minimumInputLength: 2,
                ajax: {
                    url: "{{ route('equipments.search') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.map(function(item) {
                                return {
                                    id: item.id,
                                    text: item.name + (item.reference ? ' (' + item.reference + ')' : '')
                                };
                            })
                        };
                    },
                    cache: true
                }
            });
        }

        // Initialiser les select2 existants
        $('.select2-equipment').each(function() {
            initEquipmentSelect2($(this));
        });

        $('#add-equipment').on('click', function() {
            const rowId = `eq-row-${eqIndex}`;
            const newRow = `
                <div class="row equipment-row mb-3 align-items-end" id="${rowId}">
                    <div class="col-md-5">
                        <label class="form-label">Équipement</label>
                        <select name="equipments[${eqIndex}][id]" class="form-select select2-equipment" required>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Quantité</label>
                        <input type="number" name="equipments[${eqIndex}][quantity]" class="form-control" value="1" min="1" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Date besoin</label>
                        <input type="date" name="equipments[${eqIndex}][needed_by_date]" class="form-control">
                    </div>
                    <div class="col-md-2 text-end">
                        <button type="button" class="btn btn-outline-danger remove-equipment" data-row="${rowId}">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            `;
            $('#equipment-container').append(newRow);
            initEquipmentSelect2($(`#${rowId} .select2-equipment`));
            eqIndex++;
        });

        $(document).on('click', '.remove-equipment', function() {
            const rowId = $(this).data('row');
            $(`#${rowId}`).remove();
        });
    });
</script>
@endpush

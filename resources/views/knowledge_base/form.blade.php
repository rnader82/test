@php
    $typeOptions = [
        'general' => __('locale.kb_type_general'),
        'dedicated' => __('locale.kb_type_dedicated'),
        'internal' => __('locale.kb_type_internal'),
    ];
@endphp

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="row">
    <div class="col-md-12 mb-3">
        <label for="subject" class="form-label">{{ __('locale.lbl_subject') }}</label>
        <input type="text" name="subject" id="subject" class="form-control" value="{{ old('subject', $knowledgeBase->subject ?? '') }}" required>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="type" class="form-label">{{ __('locale.lbl_type') }}</label>
        <select name="type" id="type" class="form-control select2" required onchange="toggleClientField()">
            @foreach($typeOptions as $value => $label)
                <option value="{{ $value }}" {{ (old('type', $knowledgeBase->type ?? '') == $value) ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-6 mb-3" id="client_field" style="display: {{ in_array(old('type', $knowledgeBase->type ?? ''), ['dedicated', 'internal']) ? 'block' : 'none' }};">
        <label for="client_id" class="form-label">{{ __('locale.lbl_client') }}</label>
        <select name="client_id" id="client_id" class="form-control select2">
            <option value="">{{ __('locale.select_client') }}</option>
            @foreach(\App\Models\Client::all() as $client)
                <option value="{{ $client->id }}" {{ (old('client_id', $knowledgeBase->client_id ?? '') == $client->id) ? 'selected' : '' }}>
                    {{ $client->raison_sociale }}
                </option>
            @endforeach
        </select>
    </div>
</div>

<div class="row">
    <div class="col-md-12 mb-3">
        <label for="document" class="form-label">{{ __('locale.document') }}</label>
        <input type="file" name="document" id="document" class="form-control" {{ isset($knowledgeBase->id) ? '' : 'required' }}>
        @if(isset($knowledgeBase->document_name))
            <small class="text-muted">{{ __('locale.current_file') }}: {{ $knowledgeBase->document_name }}</small>
        @endif
    </div>
</div>

@push('scripts')
<script>
    function toggleClientField() {
        const type = document.getElementById('type').value;
        const clientField = document.getElementById('client_field');
        clientField.style.display = (type === 'dedicated' || type === 'internal') ? 'block' : 'none';
    }

    document.addEventListener('DOMContentLoaded', function() {
        $('.select2').select2();
    });
</script>
@endpush

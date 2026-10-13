@extends('layouts.app')

@section('content')
<div class="container">
    <h3>{{ __('visite_prev.step_13_title') }}</h3>

    <form method="POST" action="{{ route('vp-visit-workflow.update', [$vp_visit->id]) }}?step={{ $step }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div id="backup-list">
            @php
                $oldBackups = old('backups');
            @endphp

            @if (session('errors'))
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach (session('errors') as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div id="backup-list">
                @if ($oldBackups)
                    @foreach ($oldBackups as $index => $backup)
                        @include('visite_prev.workflow.partials.config_backup_block', [
                            'backup' => (object) $backup,
                            'index' => $index,
                        ])
                    @endforeach
                @else
                    @foreach ($vp_visit->configBackups as $index => $backup)
                        @include('visite_prev.workflow.partials.config_backup_block', [
                            'backup' => $backup,
                            'index' => $index,
                        ])
                    @endforeach
                @endif
            </div>

        </div>

        <button type="button" class="btn btn-secondary mb-3" onclick="addBackupBlock()">
            <i class="bi-plus-lg"></i> {{ __('visite_prev.add_backup') }}
        </button>
        <div class="div-floating-button">
            <button type="submit" class="btn btn-primary">{{ __('visite_prev.next') }}</button>
        </div>
    </form>
</div>

<template id="backup-template">
    @php $newIndex = '__INDEX__'; @endphp
    @include('visite_prev.workflow.partials.config_backup_block', ['backup' => null, 'index' => $newIndex])
</template>


<script>
let backupIndex = {{ $vp_visit->configBackups->count() }};

function addBackupBlock() {
    const template = document.getElementById('backup-template').innerHTML;
    const html = template.replace(/__INDEX__/g, backupIndex);
    document.getElementById('backup-list').insertAdjacentHTML('beforeend', html);
    backupIndex++;
}

function removeBackupBlock(button) {
    if (confirm('{{ __("visite_prev.confirm_delete_item") }}')) {
        button.closest('.config-backup-block').remove();
    }
}
</script>
@endsection

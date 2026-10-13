<div class="card mb-3 config-backup-block" data-index="{{ $index }}">
    <div class="card-body">
        <div class="row g-3">
            <input type="hidden" name="backups[{{ $index }}][id]" value="{{ $backup->id ?? '' }}">
            
            <div class="col-md-5">
                <label class="form-label">{{ __('visite_prev.name') }}</label>
                <input type="text" name="backups[{{ $index }}][name]" class="form-control" value="{{ $backup->name ?? '' }}" placeholder="ex: Config PBX, Sauvegarde Appel-Malade...">
            </div>

            <div class="col-md-5">
                <label class="form-label">{{ __('visite_prev.file') }}</label>
                @if(isset($backup) && $backup->path)
                    <div class="mb-2">
                        <a href="{{ Storage::disk('public')->url($backup->path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-download"></i> {{ $backup->filename ?? 'Voir le fichier' }}
                        </a>
                    </div>
                @endif
                <input type="file" name="backups[{{ $index }}][file]" class="form-control">
            </div>

            <div class="col-md-2 d-flex align-items-end">
                <button type="button" class="btn btn-outline-danger w-100" onclick="removeBackupBlock(this)">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>
    </div>
</div>

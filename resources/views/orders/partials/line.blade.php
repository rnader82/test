@php
	$line = $line ?? [];
	$index = $index ?? 0;
	$typeLigne = $line['type_ligne'] ?? 'Article';
    $isOption = !empty($line['is_option']);
@endphp

@if ($typeLigne === 'Chapitre')
    <!-- Chapter Row (Warm Tan Banner like Téléphonie in screenshot) -->
    <tr data-item data-type="Chapitre" class="align-middle chapter-row" style="background-color: #f5e7d5; border-bottom: 1px solid #ebd8c1;">
        <input type="hidden" name="lines[{{ $index }}][position]" class="line-position" value="{{ $line['position'] ?? (is_numeric($index) ? $index + 1 : 1) }}">
        <input type="hidden" name="lines[{{ $index }}][type_ligne]" class="line-type-ligne" value="Chapitre">
        <input type="hidden" name="lines[{{ $index }}][chapter_id]" class="line-chapter-id" value="{{ $line['chapter_id'] ?? ('chap_' . $index) }}">
        <input type="hidden" name="lines[{{ $index }}][chapter_title]" class="line-chapter-title" value="{{ $line['chapter_title'] ?? ($line['libelle'] ?? 'Nouveau Chapitre') }}">
        <input type="hidden" name="lines[{{ $index }}][code_article]" class="line-code-article" value="">
        <input type="hidden" name="lines[{{ $index }}][equipment_id]" class="line-equipment-id" value="">
        <input type="hidden" name="lines[{{ $index }}][qte]" class="line-qte" value="0">
        <input type="hidden" name="lines[{{ $index }}][pu_ht]" class="line-pu-ht" value="0">
        <input type="hidden" name="lines[{{ $index }}][pu_ht_net]" class="line-pu-ht-net" value="0">
        <input type="hidden" name="lines[{{ $index }}][remise_ligne]" class="line-remise-ligne" value="0">
        <input type="hidden" name="lines[{{ $index }}][remise_2]" class="line-remise-2" value="0">
        <input type="hidden" name="lines[{{ $index }}][montant_ht]" class="line-montant-ht" value="0">

        <td class="text-center" style="width: 35px; cursor: pointer;">
            <i class="bi bi-chevron-up text-dark chapter-toggle-icon" style="font-size: 0.85rem;" title="Replier/Déplier"></i>
        </td>
        <td class="text-center" style="width: 35px;">
            <input class="form-check-input" type="checkbox" checked>
        </td>
        <td colspan="5" class="py-2">
            <div class="d-flex align-items-center gap-2">
                <input type="text" name="lines[{{ $index }}][libelle]" class="form-control form-control-sm fw-bold border-0 bg-transparent text-dark p-0 line-libelle" style="font-size: 14px; outline: none; box-shadow: none;" value="{{ $line['libelle'] ?? 'Téléphonie' }}" placeholder="Titre du chapitre...">
            </div>
        </td>
        <td colspan="2" class="text-end fw-bold text-dark py-2 pe-3 text-nowrap">
            <span class="small font-normal" style="font-size: 13px; font-weight: 500;">Sous-total : </span>
            <span class="chapter-subtotal-display" style="font-size: 13px; font-weight: 700;">0,00 €</span>
        </td>
        <td class="text-center" style="width: 35px;">
            <button type="button" class="btn btn-sm btn-link text-danger p-0 border-0 line-delete-btn" data-remove-item title="Supprimer le chapitre">
                <i class="bi bi-x-lg" style="font-size: 0.85rem;"></i>
            </button>
        </td>
    </tr>

@elseif ($typeLigne === 'Option')
    <!-- Option Chapter Row -->
    <tr data-item data-type="Option" class="align-middle option-chapter-row" style="background-color: #f0fdf4; border-left: 3px solid #16a34a; border-bottom: 1px solid #dcfce7;">
        <input type="hidden" name="lines[{{ $index }}][position]" class="line-position" value="{{ $line['position'] ?? (is_numeric($index) ? $index + 1 : 1) }}">
        <input type="hidden" name="lines[{{ $index }}][type_ligne]" class="line-type-ligne" value="Option">
        <input type="hidden" name="lines[{{ $index }}][is_option]" class="line-is-option" value="1">
        <input type="hidden" name="lines[{{ $index }}][code_article]" class="line-code-article" value="">
        <input type="hidden" name="lines[{{ $index }}][equipment_id]" class="line-equipment-id" value="">
        <input type="hidden" name="lines[{{ $index }}][qte]" class="line-qte" value="0">
        <input type="hidden" name="lines[{{ $index }}][pu_ht]" class="line-pu-ht" value="0">
        <input type="hidden" name="lines[{{ $index }}][pu_ht_net]" class="line-pu-ht-net" value="0">
        <input type="hidden" name="lines[{{ $index }}][remise_ligne]" class="line-remise-ligne" value="0">
        <input type="hidden" name="lines[{{ $index }}][remise_2]" class="line-remise-2" value="0">
        <input type="hidden" name="lines[{{ $index }}][montant_ht]" class="line-montant-ht" value="0">

        <td class="text-center" style="width: 35px; cursor: pointer;">
            <i class="bi bi-chevron-up text-success chapter-toggle-icon" style="font-size: 0.85rem;" title="Replier/Déplier"></i>
        </td>
        <td class="text-center" style="width: 35px;">
            <input class="form-check-input" type="checkbox">
        </td>
        <td colspan="5" class="py-2">
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-0 fw-semibold" style="font-size: 11px;">Option</span>
                <input type="text" name="lines[{{ $index }}][libelle]" class="form-control form-control-sm fw-semibold border-0 bg-transparent text-dark p-0 line-libelle" style="font-size: 13.5px; outline: none; box-shadow: none;" value="{{ $line['libelle'] ?? 'Option...' }}" placeholder="Titre de l'option...">
            </div>
        </td>
        <td colspan="2" class="text-end fw-bold text-success py-2 pe-3 text-nowrap">
            <span class="small font-normal" style="font-size: 13px;">Sous-total : </span>
            <span class="chapter-subtotal-display" style="font-size: 13px;">0,00 €</span>
        </td>
        <td class="text-center" style="width: 35px;">
            <button type="button" class="btn btn-sm btn-link text-danger p-0 border-0 line-delete-btn" data-remove-item title="Supprimer l'option">
                <i class="bi bi-x-lg" style="font-size: 0.85rem;"></i>
            </button>
        </td>
    </tr>

@elseif ($typeLigne === 'Section')
    <!-- Sub-Section Row (e.g. IPBX, Licences with blue icon in screenshot) -->
    <tr data-item data-type="Section" class="align-middle section-row" style="background-color: #ffffff; border-bottom: 1px solid #f1f5f9;">
        <input type="hidden" name="lines[{{ $index }}][position]" class="line-position" value="{{ $line['position'] ?? (is_numeric($index) ? $index + 1 : 1) }}">
        <input type="hidden" name="lines[{{ $index }}][type_ligne]" class="line-type-ligne" value="Section">
        <input type="hidden" name="lines[{{ $index }}][code_article]" class="line-code-article" value="">
        <input type="hidden" name="lines[{{ $index }}][equipment_id]" class="line-equipment-id" value="">
        <input type="hidden" name="lines[{{ $index }}][qte]" class="line-qte" value="0">
        <input type="hidden" name="lines[{{ $index }}][pu_ht]" class="line-pu-ht" value="0">
        <input type="hidden" name="lines[{{ $index }}][pu_ht_net]" class="line-pu-ht-net" value="0">
        <input type="hidden" name="lines[{{ $index }}][remise_ligne]" class="line-remise-ligne" value="0">
        <input type="hidden" name="lines[{{ $index }}][remise_2]" class="line-remise-2" value="0">
        <input type="hidden" name="lines[{{ $index }}][montant_ht]" class="line-montant-ht" value="0">

        <td class="text-center" style="width: 35px; cursor: move;">
            <i class="bi bi-arrow-down-up text-muted small" style="font-size: 0.75rem; color: #94a3b8;"></i>
        </td>
        <td class="text-center" style="width: 35px;">
            <input class="form-check-input" type="checkbox" checked>
        </td>
        <td colspan="7" class="py-2">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-chat-left-text text-primary" style="font-size: 14px;"></i>
                <input type="text" name="lines[{{ $index }}][libelle]" class="form-control form-control-sm fw-semibold border-0 bg-transparent text-primary p-0 line-libelle" style="font-size: 13px; outline: none; box-shadow: none;" value="{{ $line['libelle'] ?? 'IPBX' }}" placeholder="Nom de la sous-section...">
            </div>
        </td>
        <td class="text-center" style="width: 35px;">
            <button type="button" class="btn btn-sm btn-link text-danger p-0 border-0 line-delete-btn" data-remove-item title="Supprimer">
                <i class="bi bi-x-lg" style="font-size: 0.85rem;"></i>
            </button>
        </td>
    </tr>

@elseif ($typeLigne === 'Commentaire')
    <!-- Comment Row -->
    <tr data-item data-type="Commentaire" class="align-middle comment-row" style="background-color: #ffffff; border-bottom: 1px solid #f1f5f9;">
        <input type="hidden" name="lines[{{ $index }}][position]" class="line-position" value="{{ $line['position'] ?? (is_numeric($index) ? $index + 1 : 1) }}">
        <input type="hidden" name="lines[{{ $index }}][type_ligne]" class="line-type-ligne" value="Commentaire">
        <input type="hidden" name="lines[{{ $index }}][code_article]" class="line-code-article" value="COMMENTAIRE">
        <input type="hidden" name="lines[{{ $index }}][equipment_id]" class="line-equipment-id" value="">
        <input type="hidden" name="lines[{{ $index }}][qte]" class="line-qte" value="0">
        <input type="hidden" name="lines[{{ $index }}][pu_ht]" class="line-pu-ht" value="0">
        <input type="hidden" name="lines[{{ $index }}][pu_ht_net]" class="line-pu-ht-net" value="0">
        <input type="hidden" name="lines[{{ $index }}][remise_ligne]" class="line-remise-ligne" value="0">
        <input type="hidden" name="lines[{{ $index }}][remise_2]" class="line-remise-2" value="0">
        <input type="hidden" name="lines[{{ $index }}][montant_ht]" class="line-montant-ht" value="0">

        <td class="text-center" style="width: 35px; cursor: move;">
            <i class="bi bi-arrow-down-up text-muted small" style="font-size: 0.75rem; color: #94a3b8;"></i>
        </td>
        <td class="text-center" style="width: 35px;">
            <i class="bi bi-chat-left-text text-primary" style="font-size: 14px;"></i>
        </td>
        <td colspan="7" class="py-2">
            <input type="text" name="lines[{{ $index }}][libelle]" class="form-control form-control-sm fst-italic border-0 bg-transparent text-secondary p-0 line-libelle" style="font-size: 12.5px; outline: none; box-shadow: none;" value="{{ $line['libelle'] ?? 'Commentaire...' }}" placeholder="Tapez votre commentaire...">
        </td>
        <td class="text-center" style="width: 35px;">
            <button type="button" class="btn btn-sm btn-link text-danger p-0 border-0 line-delete-btn" data-remove-item title="Supprimer">
                <i class="bi bi-x-lg" style="font-size: 0.85rem;"></i>
            </button>
        </td>
    </tr>

@else
    <!-- Standard Article Row (Matches Screenshot 100%) -->
    <tr data-item data-type="Article" class="align-middle article-row" style="background-color: #ffffff; border-bottom: 1px solid #f1f5f9;">
        <!-- Hidden database fields -->
        <input type="hidden" name="lines[{{ $index }}][position]" class="line-position" value="{{ $line['position'] ?? (is_numeric($index) ? $index + 1 : 1) }}">
        <input type="hidden" name="lines[{{ $index }}][type_ligne]" class="line-type-ligne" value="Article">
        <input type="hidden" name="lines[{{ $index }}][equipment_id]" class="line-equipment-id" value="{{ $line['equipment_id'] ?? '' }}">
        <input type="hidden" name="lines[{{ $index }}][type_article]" value="{{ $line['type_article'] ?? '' }}">
        <input type="hidden" name="lines[{{ $index }}][qty_delivered]" value="{{ $line['qty_delivered'] ?? '' }}">
        <input type="hidden" name="lines[{{ $index }}][pu_ttc]" value="{{ $line['pu_ttc'] ?? '' }}">
        <input type="hidden" name="lines[{{ $index }}][famille_taxe1_id]" value="{{ $line['famille_taxe1_id'] ?? '' }}">
        <input type="hidden" name="lines[{{ $index }}][total_taxe1]" value="{{ $line['total_taxe1'] ?? '' }}">
        <input type="hidden" name="lines[{{ $index }}][montant_ttc]" class="line-montant-ttc" value="{{ $line['montant_ttc'] ?? '' }}">
        <input type="hidden" name="lines[{{ $index }}][chapter_id]" class="line-chapter-id" value="{{ $line['chapter_id'] ?? '' }}">
        <input type="hidden" name="lines[{{ $index }}][is_option]" class="line-is-option" value="{{ $isOption ? 1 : 0 }}">

        <!-- Column 1: Reorder handle -->
        <td class="text-center" style="width: 35px; cursor: move;">
            <i class="bi bi-arrow-down-up text-muted small" style="font-size: 0.75rem; color: #94a3b8;"></i>
        </td>

        <!-- Column 2: Checkbox -->
        <td class="text-center" style="width: 35px;">
            <input class="form-check-input line-select-check" type="checkbox" checked>
        </td>

        <!-- Column 3: Article (Code & Libelle) -->
        <td class="py-2">
            <div class="d-flex flex-column" style="max-width: 380px;">
                <input type="text" name="lines[{{ $index }}][code_article]" class="fw-bold border-0 bg-transparent p-0 line-code-article text-dark" style="font-size: 13px; outline: none; box-shadow: none;" value="{{ $line['code_article'] ?? '' }}" placeholder="Code Article" readonly>
                <input type="text" name="lines[{{ $index }}][libelle]" class="text-muted border-0 bg-transparent p-0 line-libelle text-truncate" style="font-size: 11.5px; outline: none; box-shadow: none;" value="{{ $line['libelle'] ?? '' }}" placeholder="Description / Libellé">
            </div>
        </td>

        <!-- Column 4: Quantity (Qté) - Minimalist Underline Input -->
        <td class="text-center" style="width: 55px;">
            <input type="number" step="1" name="lines[{{ $index }}][qte]" class="clean-underline-input text-center line-qte text-dark fw-medium" style="width: 40px; font-size: 13px;" value="{{ $line['qte'] ?? '1' }}" min="0">
        </td>

        <!-- Column 5: Public Price (PxPub) - Formatted Text -->
        <td class="text-end text-nowrap" style="width: 80px;">
            <span class="text-dark line-pxpub-display" style="font-size: 13px;">{{ number_format((float) ($line['pu_ht'] ?? 0), 2, ',', ' ') }} €</span>
            <input type="hidden" name="lines[{{ $index }}][pu_ht]" class="line-pu-ht" value="{{ number_format((float) ($line['pu_ht'] ?? 0), 2, '.', '') }}">
        </td>

        <!-- Column 6: Discount 1 (R1) - Minimalist Underline Input -->
        <td class="text-end text-nowrap" style="width: 60px;">
            <input type="number" step="0.01" name="lines[{{ $index }}][remise_ligne]" class="clean-underline-input text-end line-remise-ligne text-dark" style="width: 45px; font-size: 13px;" value="{{ number_format((float) ($line['remise_ligne'] ?? 0), 2, '.', '') }}" min="0" max="100">
        </td>

        <!-- Column 7: Discount 2 (R2) - Minimalist Underline Input with + -->
        <td class="text-end text-nowrap" style="width: 65px;">
            <span class="text-muted small me-1">+</span><input type="number" step="0.01" name="lines[{{ $index }}][remise_2]" class="clean-underline-input text-end line-remise-2 text-dark" style="width: 45px; font-size: 13px;" value="{{ number_format((float) ($line['remise_2'] ?? 0), 2, '.', '') }}" min="0" max="100">
        </td>

        <!-- Column 8: Net Unit Price (PuHT) - Formatted Text -->
        <td class="text-end text-nowrap" style="width: 80px;">
            <span class="text-dark line-pu-ht-net-display" style="font-size: 13px;">{{ number_format((float) ($line['pu_ht_net'] ?? ($line['pu_ht'] ?? 0)), 2, ',', ' ') }} €</span>
            <input type="hidden" name="lines[{{ $index }}][pu_ht_net]" class="line-pu-ht-net" value="{{ number_format((float) ($line['pu_ht_net'] ?? ($line['pu_ht'] ?? 0)), 2, '.', '') }}">
        </td>

        <!-- Column 9: Total Price (PxTot) - Bold Formatted Text -->
        <td class="text-end text-nowrap" style="width: 85px;">
            <span class="fw-bold text-dark line-montant-ht-display" style="font-size: 13px;">{{ number_format((float) ($line['montant_ht'] ?? 0), 2, ',', ' ') }} €</span>
            <input type="hidden" name="lines[{{ $index }}][montant_ht]" class="line-montant-ht" value="{{ number_format((float) ($line['montant_ht'] ?? 0), 2, '.', '') }}">
        </td>

        <!-- Column 10: Delete Action -->
        <td class="text-center" style="width: 35px;">
            <button type="button" class="btn btn-sm btn-link text-danger p-0 border-0 line-delete-btn" data-remove-item title="Supprimer l'article">
                <i class="bi bi-x-lg" style="font-size: 0.85rem;"></i>
            </button>
        </td>
    </tr>
@endif

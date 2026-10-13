@extends('layouts.app')

@section('content')
@if (session('errors'))
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach (session('errors') as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<div class="container">
    <h3>{{ __('visite_prev.step_3_title') }}</h3>

    <form action="{{ route('vp-visit-workflow.update', $vp_visit->id) }}?step={{ $step }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div id="equipment-list">
            @php
                $oldEquipments = old('equipments');
            @endphp

            @if ($oldEquipments)
                @foreach ($oldEquipments as $index => $equipment)
                    @include('visite_prev.workflow.partials.telecom_equipment_block', [
                        'equipment' => (object) $equipment,
                        'index' => $index
                    ])
                @endforeach
            @else
                @foreach ($vp_visit->telecomEquipments as $index => $equipment)
                    @include('visite_prev.workflow.partials.telecom_equipment_block', [
                        'equipment' => $equipment,
                        'index' => $index
                    ])
                @endforeach
            @endif
        </div>

        <button type="button" class="btn btn-secondary mb-3" onclick="addEquipmentBlock()">
            <i class="bi-plus-lg"></i>
        </button>
        <div class="div-floating-button">
            <button type="submit" class="btn btn-primary">{{ __('visite_prev.next') }}</button>
        </div>
    </form>
</div>

{{-- Template for new equipment --}}
<template id="equipment-template">
    @php $newIndex = '__INDEX__'; @endphp
    @include('visite_prev.workflow.partials.telecom_equipment_block', ['equipment' => null, 'index' => $newIndex])
</template>

<script>
    let equipmentIndex = {{ $vp_visit->telecomEquipments->count() }};

    function addEquipmentBlock() {
        const template = document.getElementById('equipment-template').innerHTML;
        const html = template.replace(/__INDEX__/g, equipmentIndex);
        document.getElementById('equipment-list').insertAdjacentHTML('beforeend', html);
        equipmentIndex++;
    }
</script>

@endsection
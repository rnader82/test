@extends('layouts.app')

@section('content')
<div class="container">
    <h3>{{ __('visite_prev.step_4_title') }}</h3>

    <form action="{{ route('vp-visit-workflow.update', $vp_visit->id) }}?step={{ $step }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div id="patient-equipments">
            @php
                $oldEquipments = old('equipments');
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

            <div id="patient-equipments">
                @if ($oldEquipments)
                    @foreach ($oldEquipments as $index => $equipment)
                        @include('visite_prev.workflow.partials.patient_call_equipment_block', [
                            'equipment' => (object) $equipment,
                            'index' => $index
                        ])
                    @endforeach
                @else
                    @foreach ($vp_visit->patientCallEquipments as $index => $equipment)
                        @include('visite_prev.workflow.partials.patient_call_equipment_block', [
                            'equipment' => $equipment,
                            'index' => $index
                        ])
                    @endforeach
                @endif
            </div>

        </div>

        <button type="button" class="btn btn-secondary mb-3" onclick="addPatientEquipmentBlock()">
            <i class="bi-plus-lg"></i>
        </button>
        <div class="div-floating-button">
            <button type="submit" class="btn btn-primary">{{ __('visite_prev.next') }}</button>
        </div>
    </form>
</div>

<template id="patient-equipment-template">
    @php $newIndex = '__INDEX__'; @endphp
    @include('visite_prev.workflow.partials.patient_call_equipment_block', ['equipment' => null, 'index' => $newIndex])
</template>

<script>
    let patientEquipmentIndex = {{ $vp_visit->patientCallEquipments->count() }};

    function addPatientEquipmentBlock() {
        const template = document.getElementById('patient-equipment-template').innerHTML;
        const html = template.replace(/__INDEX__/g, patientEquipmentIndex);
        document.getElementById('patient-equipments').insertAdjacentHTML('beforeend', html);
        patientEquipmentIndex++;
    }
</script>

@endsection
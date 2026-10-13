@extends('layouts.app')

@section('content')
<div class="container">
    <h3>{{ __('visite_prev.step_5_title') }}</h3>

    <form action="{{ route('vp-visit-workflow.update', $vp_visit->id) }}?step={{ $step }}" method="POST">
        @csrf
        @method('PUT')

        <div id="repair-interrupts">
            @php
                $oldRepairs = old('repairs');
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

            <div id="repair-interrupts">
                @if ($oldRepairs)
                    @foreach ($oldRepairs as $index => $repair)
                        @include('visite_prev.workflow.partials.repair_interrupt_block', [
                            'repair' => (object) $repair,
                            'index' => $index
                        ])
                    @endforeach
                @else
                    @foreach ($vp_visit->repairInterrupts as $index => $repair)
                        @include('visite_prev.workflow.partials.repair_interrupt_block', [
                            'repair' => $repair,
                            'index' => $index
                        ])
                    @endforeach
                @endif
            </div>

        </div>

        <button type="button" class="btn btn-secondary mb-3" onclick="addRepairInterruptBlock()">
            <i class="bi-plus-lg"></i>
        </button>
        <div class="div-floating-button">
            <button type="submit" class="btn btn-primary">{{ __('visite_prev.next') }}</button>
        </div>
    </form>
</div>

<template id="repair-interrupt-template">
    @php $newIndex = '__INDEX__'; @endphp
    @include('visite_prev.workflow.partials.repair_interrupt_block', ['repair' => null, 'index' => $newIndex])
</template>



<script>
    let repairIndex = {{ $vp_visit->repairInterrupts->count() }};

    function addRepairInterruptBlock() {
        const template = document.getElementById('repair-interrupt-template').innerHTML;
        const html = template.replace(/__INDEX__/g, repairIndex);
        document.getElementById('repair-interrupts').insertAdjacentHTML('beforeend', html);
        repairIndex++;
    }
</script>

@endsection
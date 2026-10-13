@extends('layouts.app')

@section('content')
<div class="container">
    <h3>{{ __('visite_prev.step_6_title') }}</h3>

    <form action="{{ route('vp-visit-workflow.update', $vp_visit->id) }}?step={{ $step }}" method="POST">
        @csrf
    @method('PUT')
        <div id="repair-elevators">
            @php
                $oldElevators = old('elevators');
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

            <div id="repair-elevators">
                @if ($oldElevators)
                    @foreach ($oldElevators as $index => $elevator)
                        @include('visite_prev.workflow.partials.repair_elevator_block', [
                            'elevator' => (object) $elevator,
                            'index' => $index
                        ])
                    @endforeach
                @else
                    @foreach ($vp_visit->repairElevators as $index => $elevator)
                        @include('visite_prev.workflow.partials.repair_elevator_block', [
                            'elevator' => $elevator,
                            'index' => $index
                        ])
                    @endforeach
                @endif
            </div>

        </div>

        <button type="button" class="btn btn-secondary mb-3" onclick="addRepairElevatorBlock()">
            <i class="bi-plus-lg"></i>
        </button>
        <div class="div-floating-button">
            <button type="submit" class="btn btn-primary">{{ __('visite_prev.next') }}</button>
        </div>
    </form>
</div>

<template id="repair-elevator-template">
    @php $newIndex = '__INDEX__'; @endphp
    @include('visite_prev.workflow.partials.repair_elevator_block', ['elevator' => null, 'index' => $newIndex])
</template>



<script>
    let elevatorIndex = {{ $vp_visit->repairElevators->count() }};

    function addRepairElevatorBlock() {
        const template = document.getElementById('repair-elevator-template').innerHTML;
        const html = template.replace(/__INDEX__/g, elevatorIndex);
        document.getElementById('repair-elevators').insertAdjacentHTML('beforeend', html);
        elevatorIndex++;
    }
</script>
@endsection

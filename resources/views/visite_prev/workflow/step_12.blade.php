@extends('layouts.app')

@section('content')
<div class="container">
    <h3>{{ __('visite_prev.step_12_title') }}</h3>

    <form action="{{ route('vp-visit-workflow.update', $vp_visit->id) }}?step={{ $step }}" method="POST">
        @csrf
        @method('PUT')

        <div id="dect-controls">
            @php
                $oldControls = old('controls');
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

            <div id="dect-controls">
                @if ($oldControls)
                    @foreach ($oldControls as $index => $control)
                        @include('visite_prev.workflow.partials.dect_control_block', ['control' => (object) $control, 'index' => $index])
                    @endforeach
                @else
                    @foreach ($vp_visit->dectControls as $index => $control)
                        @include('visite_prev.workflow.partials.dect_control_block', ['control' => $control, 'index' => $index])
                    @endforeach
                @endif
            </div>

        </div>

        <button type="button" class="btn btn-secondary mb-3" onclick="addDectBlock()">
            <i class="bi-plus-lg"></i>
        </button>
        <div class="div-floating-button">
            <button type="submit" class="btn btn-primary">{{ __('visite_prev.next') }}</button>
        </div>
    </form>
</div>

<template id="dect-control-template">
    @php $newIndex = '__INDEX__'; @endphp
    @include('visite_prev.workflow.partials.dect_control_block', ['control' => null, 'index' => $newIndex])
</template>

<script>
    let dectIndex = {{ $vp_visit->dectControls->count() }};

    function addDectBlock() {
        const template = document.getElementById('dect-control-template').innerHTML;
        const html = template.replace(/__INDEX__/g, dectIndex);
        document.getElementById('dect-controls').insertAdjacentHTML('beforeend', html);
        dectIndex++;
    }
</script>
@endsection

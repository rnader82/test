@extends('layouts.app')
@section('portal-module', 'visite_prev')
@section('portal-page', 'workflow')
@section('portal-kind', 'workflow')
@section('portal-family', 'technical')
@section('portal-icon', 'screwdriver-wrench')


@section('content')
<div class="container">
    <h3>{{ __('visite_prev.step_7_title') }}</h3>

    <form action="{{ route('vp-visit-workflow.update', $vp_visit->id) }}?step={{ $step }}" method="POST">
        @csrf
        @method('PUT')

        <div id="help-lines">
            @php
                $oldHelpLines = old('help_lines');
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

            <div id="help-lines">
                @if ($oldHelpLines)
                    @foreach ($oldHelpLines as $index => $line)
                        @include('visite_prev.workflow.partials.repair_help_line_block', [
                            'line' => (object) $line,
                            'index' => $index
                        ])
                    @endforeach
                @else
                    @foreach ($vp_visit->repairHelpLines as $index => $line)
                        @include('visite_prev.workflow.partials.repair_help_line_block', [
                            'line' => $line,
                            'index' => $index
                        ])
                    @endforeach
                @endif
            </div>

        </div>

        <button type="button" class="btn btn-secondary mb-3" onclick="addHelpLineBlock()">
            <i class="bi-plus-lg"></i>
        </button>
        <div class="div-floating-button">
            <button type="submit" class="btn btn-primary">{{ __('visite_prev.next') }}</button>
        </div>
    </form>
</div>

<template id="repair-help-line-template">
    @php $newIndex = '__INDEX__'; @endphp
    @include('visite_prev.workflow.partials.repair_help_line_block', ['line' => null, 'index' => $newIndex])
</template>



<script>
    let helpLineIndex = {{ $vp_visit->repairHelpLines->count() }};

    function addHelpLineBlock() {
        const template = document.getElementById('repair-help-line-template').innerHTML;
        const html = template.replace(/__INDEX__/g, helpLineIndex);
        document.getElementById('help-lines').insertAdjacentHTML('beforeend', html);
        helpLineIndex++;
    }
</script>
@endsection

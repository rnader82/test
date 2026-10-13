@extends('layouts.app')

@section('content')
<div class="container">
    <h3>{{ __('visite_prev.step_8_title') }}</h3>

    <form action="{{ route('vp-visit-workflow.update', $vp_visit->id) }}?step={{ $step }}" method="POST">
        @csrf
        @method('PUT')

        <div id="inputs-list">
            @php
                $oldInputs = old('inputs');
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

            <div id="inputs-list">
                @if ($oldInputs)
                    @foreach ($oldInputs as $index => $input)
                        @include('visite_prev.workflow.partials.input_block', [
                            'input' => (object) $input,
                            'index' => $index
                        ])
                    @endforeach
                @else
                    @foreach ($vp_visit->inputs as $index => $input)
                        @include('visite_prev.workflow.partials.input_block', [
                            'input' => $input,
                            'index' => $index
                        ])
                    @endforeach
                @endif
            </div>

        </div>

        <button type="button" class="btn btn-secondary mb-3" onclick="addInputBlock()">
            <i class="bi-plus-lg"></i>
        </button>
        <div class="div-floating-button">
            <button type="submit" class="btn btn-primary">{{ __('visite_prev.next') }}</button>
        </div>
    </form>
</div>

<template id="input-template">
    @php $newIndex = '__INDEX__'; @endphp
    @include('visite_prev.workflow.partials.input_block', ['input' => null, 'index' => $newIndex])
</template>

<script>
    let inputIndex = {{ $vp_visit->inputs->count() }};

    function addInputBlock() {
        const template = document.getElementById('input-template').innerHTML;
        const html = template.replace(/__INDEX__/g, inputIndex);
        document.getElementById('inputs-list').insertAdjacentHTML('beforeend', html);
        inputIndex++;
    }
</script>
@endsection

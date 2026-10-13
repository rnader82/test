@extends('layouts.app')

@section('content')
<div class="container">
    <h3>{{ __('visite_prev.step_14_title') }}</h3>

    <form method="POST" action="{{ route('vp-visit-workflow.update', [$vp_visit->id]) }}?step={{ $step }}">
        @csrf
        @method('PUT')

        <div id="stock-list">
            @php
                $oldStocks = old('stocks');
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

            <div id="stock-list">
                @if ($oldStocks)
                    @foreach ($oldStocks as $index => $stock)
                        @include('visite_prev.workflow.partials.stock_block', [
                            'stock' => (object) $stock,
                            'index' => $index,
                        ])
                    @endforeach
                @else
                    @foreach ($vp_visit->stocks as $index => $stock)
                        @include('visite_prev.workflow.partials.stock_block', [
                            'stock' => $stock,
                            'index' => $index,
                        ])
                    @endforeach
                @endif
            </div>

        </div>

        <button type="button" class="btn btn-secondary mb-3" onclick="addStockItem()">
            <i class="bi-plus-lg"></i>  
        </button>
        <div class="div-floating-button">
            <button type="submit" class="btn btn-primary">{{ __('visite_prev.finish') }}</button>
        </div>
    </form>
</div>

<template id="stock-template">
    @php $newIndex = '__INDEX__'; @endphp
    @include('visite_prev.workflow.partials.stock_block', ['stock' => null, 'index' => $newIndex])
</template>


<script>
let stockIndex = {{ $vp_visit->stocks->count() }};

function addStockItem() {
    const html = document.getElementById('stock-template').innerHTML.replace(/__INDEX__/g, stockIndex);
    document.getElementById('stock-list').insertAdjacentHTML('beforeend', html);
    stockIndex++;
}
</script>
@endsection

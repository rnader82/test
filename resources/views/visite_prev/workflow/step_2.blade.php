@extends('layouts.app')
@section('portal-module', 'visite_prev')
@section('portal-page', 'workflow')
@section('portal-kind', 'workflow')
@section('portal-family', 'technical')
@section('portal-icon', 'screwdriver-wrench')


@section('content')

<div class="container">
    <h3 class="mb-4">{{ __('visite_prev.step_2_title') }}</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('errors'))
        @php
            $error_ids = $errors['ids'];
        @endphp
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach(session('errors')['messages'] as $message)
                    <li>{{ $message }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('vp-visit-workflow.update', $vp_visit->id) }}?step={{ $step }}" method="POST">
        @csrf
        @method('PUT')

        @foreach ($vp_visit->checklistItems as $item)
            @if(isset($error_ids) && in_array($item->id, $error_ids))
                <div class="preventive-panel card mb-3 shadow-sm border-danger">
            @else
                <div class="preventive-panel card mb-3 shadow-sm">
            @endif

                <div class="card-body">
                    <h5 class="card-title">
                        {{ $item->order_number }}. {{ $item->action }}
                    </h5>

                    <div class="row mb-2">
                        <div class="col-md-2 d-flex align-items-center">
                            <div class="form-check">
                                <input type="checkbox"
                                       class="form-check-input"
                                       name="items[{{ $item->id }}][done]"
                                       id="done-{{ $item->id }}"
                                       {{ $item->done ? 'checked' : '' }}>
                                <label class="form-check-label" for="done-{{ $item->id }}">
                                    {{ __('visite_prev.done') }}
                                </label>
                            </div>
                        </div>

                        <div class="col-md-5">
                            <label for="comments-{{ $item->id }}" class="form-label">{{ __('visite_prev.comments') }}</label>
                            <input type="text"
                                   name="items[{{ $item->id }}][comments]"
                                   id="comments-{{ $item->id }}"
                                   class="form-control"
                                   value="{{ $item->comments }}"
                                   placeholder="{{ __('visite_prev.enter_comments') }}">
                        </div>

                        <div class="col-md-5">
                            <label for="todo-{{ $item->id }}" class="form-label">{{ __('visite_prev.todo') }}</label>
                            <input type="text"
                                   name="items[{{ $item->id }}][todo]"
                                   id="todo-{{ $item->id }}"
                                   class="form-control"
                                   value="{{ $item->todo }}"
                                   placeholder="{{ __('visite_prev.enter_todo') }}">
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <div class="d-flex justify-content-end">
            <div class="div-floating-button">
                <button type="submit" class="btn btn-primary">
                    {{ __('visite_prev.next') }}
                </button>
            </div>
            
        </div>
    </form>
</div>
@endsection

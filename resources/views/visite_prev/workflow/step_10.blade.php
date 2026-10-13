@extends('layouts.app')

@section('content')
<div class="container">
    <h3>{{ __('visite_prev.step_10_title') }}</h3>

    <form method="POST" action="{{ route('vp-visit-workflow.update', [$vp_visit->id]) }}?step={{ $step }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="quill-editor-admin">{{ __('visite_prev.common_points_discussed_admin') }}</label>
        
            <!-- Quill visual editor -->
            <div id="quill-editor-admin" style="height: 150px;">
                {!! old('common_points_discussed_admin', $vp_visit->common_points_discussed_admin) !!}
            </div>
        
            <!-- Hidden input for form submission -->
            <input type="hidden" name="common_points_discussed_admin" id="common_points_discussed_admin">
        </div>

        <div id="attendees">
            @php
                $oldAttendees = old('attendees');
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

            <div id="attendees">
                @if ($oldAttendees)
                    @foreach ($oldAttendees as $index => $attendee)
                        @include('visite_prev.workflow.partials.training_admin_block', ['attendee' => (object) $attendee, 'index' => $index])
                    @endforeach
                @else
                    @foreach ($vp_visit->trainingAdmins as $index => $attendee)
                        @include('visite_prev.workflow.partials.training_admin_block', ['attendee' => $attendee, 'index' => $index])
                    @endforeach
                @endif
            </div>

        </div>

        <div class="div-floating-button">
            <button type="submit" class="btn btn-primary">{{ __('visite_prev.next') }}</button>
        </div>
        <button type="button" class="btn btn-secondary mb-3" onclick="addAttendee()">
            <i class="bi bi-plus-lg"></i>
        </button>

        
    </form>
</div>

<template id="attendee-template">
    @php $newIndex = '__INDEX__'; @endphp
    @include('visite_prev.workflow.partials.training_admin_block', ['attendee' => null, 'index' => $newIndex])
</template>

<!-- jSignature JS and jQuery dependency -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"
    integrity="sha384-vtXRMe3mGCbOeY7l30aIg8H9p3GdeSe4IFlP6G8JMa7o7lXvnz3GFKzPxzJdPfGK" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/jsignature@2.1.2/libs/jSignature.min.js"
    integrity="sha384-vRBjKT+yCoW+2UnYuSGj2cz5/WD5n6Dp5HHMGxRXGceMwULMn0Uf4TcAk3rYyGlk" crossorigin="anonymous"></script>

<!-- jSignature CSS -->
<style>
    .signature-box {
        border: 1px solid #ccc;
        width: 100%;
        height: 250px;
        background-color: white;
    }
</style>

<script>
let attendeeIndex = {{ $vp_visit->trainingAdmins->count() ?? 0 }};

function addAttendee() {
    const currentIndex = attendeeIndex;
    attendeeIndex++;

    const template = document.getElementById('attendee-template').innerHTML;
    const html = template.replace(/__INDEX__/g, currentIndex);
    document.getElementById('attendees').insertAdjacentHTML('beforeend', html);

    setTimeout(() => initJSignature(currentIndex), 100);
}

function initJSignature(index) {
    const $sigBox = $(`#signature-box-${index}`);
    const $input = $(`#signature-${index}`);

    if (!$sigBox.length || !$input.length) return;

    $sigBox.jSignature({ width: '100%', height: 250, backgroundColor: '#fff' });

    $sigBox.on('change', function () {
        const dataPair = $sigBox.jSignature('getData', 'default');
        $input.val(dataPair);
    });
}

$(document).ready(function () {
    for (let i = 0; i < attendeeIndex; i++) {
        initJSignature(i);
    }
});
</script>

<script>
    
    document.addEventListener('DOMContentLoaded', function () {
        // Initialize value on page load

        const quill = new Quill('#quill-editor-admin', {
            theme: 'snow'
        });

        // Set initial value from server into Quill (optional if already rendered inside the div)
        // quill.root.innerHTML = `{!! old('common_points_discussed', $vp_visit->common_points_discussed) !!}`;

        const inputQuill = document.getElementById('common_points_discussed_admin');

        // Update hidden input on any content change
        quill.on('text-change', function () {
            inputQuill.value = quill.root.innerHTML;
        });
    });
</script>


@endsection

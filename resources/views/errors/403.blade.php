@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>403 Forbidden</h1>
        <p>You do not have permission to access this page.</p>
        <a href="{{ url()->previous() }}" class="btn btn-primary"><i class="bi bi-arrow-left"></i></a>
    </div>
@endsection

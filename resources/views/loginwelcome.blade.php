@extends('layouts.app')
@section('portal-module', 'loginwelcome')
@section('portal-page', 'loginwelcome')
@section('portal-kind', 'record')
@section('portal-family', 'admin')
@section('portal-icon', 'gear')

@section('content')
    <!-- Content wrapper -->
    <div class="content-wrapper">

        <!-- Content -->

        <div class="container-xxl flex-grow-1 container-p-y">

            <div class="h-100 d-flex align-items-center justify-content-center">
                <h1>{{ __('locale.lbl_welcome_novalyo_360') }}</h1>
            </div>
            <!-- <form id="refreshClientForm2" method="POST" action="/api/clients/import" class="ms-auto" style="width: 142px;">
                @csrf
                <input type="hidden" value="client" name="file_type"/>
                <button type="submit" class="btn btn-primary ms-0">Refresh Clients
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
            </form> -->
        </div>
        <!-- / Content -->
    </div>
    <!-- Content wrapper -->
@endsection

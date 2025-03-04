@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">You are blocked</div>

                <div class="card-body">
                    {{-- @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('A fresh verification link has been sent to your email address.') }}
                        </div>
                    @endif --}}

                    Please contact the administrator to request unblocking. <a href="mailto:{{ config('notetracker.admin_contact_mail') }}">{{ config('notetracker.admin_contact_mail') }}</a> <br>
                    Thank you.
                    {{-- <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('click here to request another') }}</button>.
                    </form> --}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

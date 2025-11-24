@extends('layouts.slaves')

@section('content')
<div class="container">

    <h3>Vérification du numéro</h3>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('verify.otp') }}">
        @csrf

        <input type="hidden" name="phone_number" value="{{ $phoneNumber }}">
        <input type="hidden" name="type" value="{{ $type }}">

        <div class="form-group">
            <label>Code OTP reçu</label>
            <input type="text" maxlength="6" class="form-control" name="otp" required>
        </div>

        <button class="btn btn-primary mt-3">Valider</button>
    </form>

    <form method="POST" action="{{ route('resend.otp') }}" class="mt-3">
        @csrf
        <input type="hidden" name="phone_number" value="{{ $phoneNumber }}">
        <button class="btn btn-link">Renvoyer le code</button>
    </form>

</div>
@endsection

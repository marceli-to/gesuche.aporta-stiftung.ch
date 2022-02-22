@extends('layout.guest')
@section('seo_title', 'Passwort vergessen')
@section('content')
<section class="content auth">
  <div>
    <h1>Passwort vergessen</h1>
    <div>
      <p>{{__('messages.password_recovery')}}</p>
      @if ($errors->any())
        <x-alert type="danger" message="{{__('messages.general_error')}}" />
      @endif
      @if (session('status'))
        <x-alert type="success" message="{{ session('status') }}" />
      @endif
      <form method="POST" class="auth auth--recover" action="{{ route('password.email') }}">
        @csrf
        <x-text-field label="E-Mail" type="email" name="email" />
        <div class="form-buttons align-justify">
          <x-button label="Senden" name="register" btnClass="btn-primary js-btn-loader" type="submit" />
          <a href="{{ route('login') }}" class="form-helper">Zurück</a>
        </div>
      </form>
    </div>
  </div>
</section>
@endsection
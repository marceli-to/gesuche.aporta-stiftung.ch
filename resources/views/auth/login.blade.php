@extends('layout.guest')
@section('seo_title', 'Login')
@section('content')
<section class="content auth">
  <div>
    <h1>Login</h1>
    @if ($errors->any())
      <x-alert type="danger" message="{{__('messages.general_error')}}" />
    @endif
    <form method="POST" class="auth" action="{{ route('login') }}">
      @csrf
      <x-text-field label="E-Mail" type="email" name="email" autocomplete="false" />
      <x-text-field label="Passwort" type="password" name="password" autocomplete="false" />
      <div class="form-buttons align-justify">
        <x-button label="Anmelden" name="register" btnClass="btn-primary js-btn-loader" type="submit" />
        @if (Route::has('password.request'))
          <a href="{{ route('password.request') }}" class="form-helper">Passwort vergessen?</a>
        @endif
      </div>
    </form>
  </div>
</section>
@endsection
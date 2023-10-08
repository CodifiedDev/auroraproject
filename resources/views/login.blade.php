@extends('wizards')
@section('title', 'Login')
@section('content')
    <form method="post">
        @csrf
        <sl-input name="email" label="Email Address" type="email" required></sl-input> <br>
        <sl-input name="password" label="Password" type="password" password-toggle required></sl-input> <br>
        <sl-button type="submit" variant="primary" style="width: 100%">Login</sl-button> <br>
    </form>
    <br>
    <sl-button style="width: 100%" href="/register">Don't have an account?</sl-button>
@endsection

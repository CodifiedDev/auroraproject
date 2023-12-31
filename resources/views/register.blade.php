@extends('wizards')
@section('title', 'Register')
@section('content')
    <form method="post">
        @csrf
        <sl-input name="username" label="Name" help-text="Use your real name, we'll set profiles up later" required></sl-input> <br>
        <sl-input name="email" label="Email Address" type="email" help-text="We'll send a verification email to this address" required></sl-input> <br>
        <sl-input name="dob" label="Date of Birth" type="date" help-text="We'll use this to verify your age" required></sl-input> <br>
        <sl-input name="password" label="Password" type="password" help-text="Use a strong password" password-toggle required></sl-input> <br>
        <sl-button type="submit" variant="primary" style="width: 100%">Create Account</sl-button> <br>
    </form>
    <br>
    <sl-button style="width: 100%" href="/login">Already have an account?</sl-button>
@endsection

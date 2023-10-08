@extends('wizards')
@section('title', 'New Profile')
@section('content')
    <form method="post">
        @csrf
        <sl-input name="username" label="Username" required></sl-input> <br>
        <sl-input name="location" label="Location"></sl-input> <br>
        <sl-button type="submit" variant="primary" style="width: 100%">Create Profile</sl-button> <br>
    </form>
@endsection

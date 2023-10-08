@extends('wizards')
@section('title', 'New Title')
@section('content')
    <form method="post">
        @csrf
        <sl-input name="title" label="Title" required></sl-input> <br>
        <sl-input name="tags" label="Tags" help-text="Please separate with semicolons (;)"></sl-input> <br>
        <sl-textarea name="description" label="Description"></sl-textarea> <br>
        <sl-button variant="primary" type="submit" style="width:100%">Confirm</sl-button>
    </form>
@endsection

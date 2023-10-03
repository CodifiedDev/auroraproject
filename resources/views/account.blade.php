@extends('app')
@section('title', 'Aurora - Account')
@section('head')
    <style>
        .profileselector {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr 1fr;
            column-gap: 6rem;
            grid-template-rows: auto auto auto;
            row-gap: 1rem;
            text-align: center;
        }
    </style>

@endsection
@section('content')
    <div style="height: 93vh; display: flex; justify-content: center; align-items: center; top: 0">
        <div class="profileselector">
            <h2 style="grid-column: 1; grid-row: 1;">Professional</h2>
            <h2 style="grid-column: 2; grid-row: 1;">Personal</h2>
            <h2 style="grid-column: 3; grid-row: 1;">Private</h2>
            <h2 style="grid-column: 4; grid-row: 1;">Anonymous</h2>
            <sl-avatar style="grid-column: 1; grid-row: 2; --size: 15rem;" label="avatar"></sl-avatar>
            <sl-avatar style="grid-column: 2; grid-row: 2; --size: 15rem" label="avatar"></sl-avatar>
            <sl-avatar style="grid-column: 3; grid-row: 2; --size: 15rem" label="avatar"></sl-avatar>
            <sl-avatar style="grid-column: 4; grid-row: 2; --size: 15rem" label="avatar"></sl-avatar>
            <sl-button style="grid-column: 1; grid-row: 3; width: 100%" href="/dashboard?profile=1">Select</sl-button>
            <sl-button style="grid-column: 2; grid-row: 3; width: 100%" href="/dashboard?profile=2">Select</sl-button>
            <sl-button style="grid-column: 3; grid-row: 3; width: 100%" href="/dashboard?profile=3">Select</sl-button>
            <sl-button style="grid-column: 4; grid-row: 3; width: 100%" href="/dashboard?profile=4">Select</sl-button>
        </div>
    </div>
@endsection

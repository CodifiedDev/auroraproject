@extends('app')
@section('title', 'Aurora - Compose')
@section('head')
<script>
</script>
<link rel="stylesheet" href="https://cdn.rawgit.com/xcatliu/simplemde-theme-dark/master/dist/simplemde-theme-dark.min.css">
<script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>
<!-- TODO: Make a local version -->
<?php
$contentDB = \Illuminate\Support\Facades\DB::table('writtenContent')->where('titleID', request()->get('titleid'))->first();
$pageDB = \Illuminate\Support\Facades\DB::table('writtenContentPages')->where('titleID', session('titleID'))->where('pageNumber', request()->get('page'))->first();
?>
@endsection
@section('content')
    <form method="post">
<div style="margin-top: 6vh">
    <h1>Compose</h1>
    <sl-input name="chaptitle" label="Chapter Title"></sl-input> <br>
</div>

<div id="editor">
<textarea id="mainEdit"></textarea>
</div>
        <div>
            <sl-button type="submit" value="1" variant="primary">Submit</sl-button>
            <sl-button type="submit" value="2">Save as Draft</sl-button>
        </div>
        <div style="float: right">
            <sl-button variant="neutral" href="<?php echo '/dashboard/compose?titleid='. request()->get('titleid'). '&page='. (request()->get('page') - 1)?>">Previous Page</sl-button>
            <h2 style="display: inline; color: var(--sl-color-neutral-500)"><?php echo request()->get('page') ?></h2>
            <sl-button variant="neutral" href="<?php echo '/dashboard/compose?titleid='. request()->get('titleid'). '&page='. (request()->get('page') + 1)?>">Next Page</sl-button>
        </div>
<script>
    var simplemde = new SimpleMDE({ element: document.getElementById("mainEdit") });
    var newtext = simplemde.value();
</script>
    </form>
@endsection

@extends('app')
@section('title', 'Aurora - Dashboard')
@section('head')
    <?php
    $profile = session('profile');
    if ($profile == 4) {
        $userid = 0;
    }
    else {
        $userid = DB::table('authenticatedSessions')->where('loginToken', session('user'))->first()->userid;
    }
    $profileinfo = DB::table('profiles')->where('userid', $userid)->where('profileType', $profile)->first();
    $name = $profileinfo->username;

    ?>
    <style>
        .dashboardContent {
            display: grid;
            grid-template-columns: 5fr 1fr;
            grid-template-rows: auto auto;
            margin-top: 4rem;
        }
    </style>
    <?php
        function makeBookCard($imgbinary, $title, $description, $authorid, $authorname, $titleid) {
            $card ='
            <sl-card style="max-width: 15vw">
                <img slot="image" alt="Cover page image" src="'. $imgbinary .'">

                    <strong>'. $title .'</strong> <br>
                    '. $description .' <br>
                    <a href="/profile?pid='. $authorid .'" style="color: var(--sl-color-neutral-500)"><small>'. $authorname .'</small></a>
                    <div slot="footer" style="display: flex; justify-content: space-between; align-items: center;">
                        <sl-button-group>
                            <sl-button variant="primary" pill>Read</sl-button>
                            <sl-dropdown placement="bottom-end">
                                <script>
                                    function redirectProperties() {
                                        window.location.href = "/dashboard?titleid='. $titleid .'";
                                    }
                                    function redirectCompose() {
                                        window.location.href = "/dashboard/compose?titleid='. $titleid .'";
                                    }
                                </script>
                                <sl-button slot="trigger" variant="primary" caret pill>
                                    <sl-visually-hidden>More options</sl-visually-hidden>
                                </sl-button>
                                <sl-menu>
                                    <sl-menu-item>Change Visability</sl-menu-item>
                                    <sl-menu-item>Archive</sl-menu-item>
                                    <sl-menu-item>Delete</sl-menu-item>
                                    <sl-menu-item onclick="redirectProperties()">Modify Properties</sl-menu-item>
                                    <sl-menu-item onclick="redirectCompose()">Edit</sl-menu-item>
                                </sl-menu>
                            </sl-dropdown>
                        </sl-button-group>
                    </div>
                </sl-card>';
            return $card;
        }
    ?>
@endsection
@section('content')
    <sl-dialog label="Change Image" class="imageChange">
        <form method="post">
            @csrf
            <input type="file">
            <sl-button slot="footer" variant="primary">Update Profile Picture</sl-button>
        </form>
    </sl-dialog>
    <sl-dialog <?php if (request()->get('titleid') != null) echo 'open'?> style="--width: 50vw;" id="titlemod">
        <?php
        $titleID = request()->get('titleid');
        $titleDB = \Illuminate\Support\Facades\DB::table('writtenContent')->where('titleID', $titleID)->first();
        ?>
        <img src="<?php if ($titleDB != null) {echo $titleDB->coverImage;}?>" style="width: 100%";}>
        <sl-input name="title" label="Title" value="<?php if ($titleDB != null) {echo $titleDB->title;}?>"></sl-input> <br>
        <sl-input name="tags" label="Tags" value="<?php if ($titleDB != null) {echo $titleDB->tags;}?>" help-text="Please separate with semicolons (;)"></sl-input> <br>
        <sl-textarea name="description" label="Description" value="<?php if ($titleDB != null) {echo $titleDB->miniDescription;}?>"></sl-textarea> <br>
        <sl-divider></sl-divider>
        <h2>Cover Image</h2>
        <input type="file"> <br>
        <sl-divider></sl-divider>
        <sl-button variant="primary">Confirm</sl-button>
    </sl-dialog>
    <script>
        const dialog = document.querySelector('#titlemod');
        dialog.addEventListener('sl-request-close', event => {
            window.location.href = '/dashboard';
        });
    </script>
    <div class="dashboardContent">
        <div style="grid-column: 1; grid-row: 1">
            <h1>Hey <?php echo $name?></h1>
            <h2 style="color: var(--sl-color-neutral-500)">Lets jump in!</h2>
            <!-- Place bio here -->
        </div>
        <div style="grid-column: 2; grid-row: 1">
            <sl-avatar style="--size: 20rem;" label="avatar" onclick="document.querySelector('.imageChange').show()"></sl-avatar>
        </div>
    </div>
    <div>
<sl-tab-group>
    <sl-tab slot="nav" panel="published">Published Content</sl-tab>
    <sl-tab slot="nav" panel="drafts">Drafts</sl-tab>
    <sl-tab-panel name="published">
        <!-- TODO: Add code to systematically add published content -->
        <div class="bookContain">
            <?php
            $author = \Illuminate\Support\Facades\DB::table('profiles')->where('userid', $userid)->where('profileType', $profile)->first();
            $publishedContent = DB::table('writtenContent')->where('authorID', $author->profileID)->where('visability', 1)->get();
            foreach ($publishedContent as $content) {
                echo makeBookCard($content->coverImage, $content->title, $content->miniDescription, $profile, $author->username, $content->titleID);
            }
            ?>

        </div>
    </sl-tab-panel>
    <sl-tab-panel name="drafts">
        <!-- TODO: Add code to systematically add published content -->
        <div class="bookContain">
            <?php
            $author = \Illuminate\Support\Facades\DB::table('profiles')->where('userid', $userid)->where('profileType', $profile)->first();
            $publishedContent = DB::table('writtenContent')->where('authorID', $author->profileID)->where('visability', 0)->get();
            foreach ($publishedContent as $content) {
                echo makeBookCard($content->coverImage, $content->title, $content->miniDescription, $profile, $author->username, $content->titleID);
            }
            ?>
        </div>
    </sl-tab-panel>
</sl-tab-group>
    </div>
    <div>
        <sl-tooltip content="Create new draft">
        <sl-icon-button name="plus-circle" label="Add" style="font-size: 36px; position: fixed; bottom: 2rem; right: 2rem" href="/dashboard/compose" <?php  if ($profile == 4) {echo 'disabled';} ?>></sl-icon-button>
        </sl-tooltip>
    </div>
@endsection

<!DOCTYPE html>
<html lang="en" class="sl-theme-dark">
<head>
    <title>Content Properties</title>
    @vite("resources/js/app.js", "vendor/courier/build")
    <?php
    $profile = \Illuminate\Support\Facades\DB::table('profiles')->where('profileID', session('profile'))->first();
    $titleID = \Illuminate\Support\Facades\Session::get('titleid');
    $titleDB = \Illuminate\Support\Facades\DB::table('writtenContent')->where('titleID', $titleID)->first();
    ?>
</head>
<body>
<img src="<?php echo $titleDB->coverImage;?>" style="width: 100%">
<sl-input name="title" label="Title" value="<?php echo $titleDB->title;?>"></sl-input> <br>
<sl-input name="tags" label="Tags" value="<?php echo $titleDB->tags;?>" help-text="Please separate with semicolons (;)"></sl-input> <br>
<sl-textarea name="description" label="Description" value="<?php echo $titleDB->miniDescription;?>"></sl-textarea> <br>
<sl-divider></sl-divider>
<h2>Cover Image</h2>
<input type="file"> <br>
<sl-divider></sl-divider>
<sl-button variant="primary">Confirm</sl-button>
</body>
</html>

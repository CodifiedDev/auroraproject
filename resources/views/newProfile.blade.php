<!DOCTYPE html>
<html lang="en" class="sl-theme-dark">
<head>
    <title>Aurora - New Profile</title>
    @vite("resources/js/app.js", "vendor/courier/build")
</head>
<body>
<div style="height: 98vh; display: flex; justify-content: center; align-items: center; top: 0">
    <sl-card style="max-width: 30vw; min-width: 20vw; max-height: 70vh; min-height: 30vh;">
        <div style="text-align: center">
            <h2>Let's set up your <?php //TODO: echo profile name ?> profile</h2>
        </div>
        <form method="post">
            @csrf
            <sl-input name="username" label="Username" required></sl-input> <br>
            <sl-input name="location" label="Location"></sl-input> <br>
            <sl-button type="submit" variant="primary" style="width: 100%">Create Profile</sl-button> <br>
        </form>
    </sl-card>
</div>
</body>
</html>

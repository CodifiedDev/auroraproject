<!DOCTYPE html>
<html lang="en" class="sl-theme-dark">
<head>
    <title>Aurora - Register</title>
    @vite("resources/js/app.js", "vendor/courier/build")

</head>
<body>
<div style="height: 98vh; display: flex; justify-content: center; align-items: center; top: 0">
<sl-card style="max-width: 30vw; min-width: 20vw; max-height: 70vh; min-height: 30vh;">
    <div style="text-align: center">
        <h2>Register</h2>
    </div>
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
</sl-card>
</div>
</body>
</html>

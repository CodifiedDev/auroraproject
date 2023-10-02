<!DOCTYPE html>
<html lang="en" class="sl-theme-dark">
<head>
    <title>Aurora - Account</title>
    @vite("resources/js/app.js", "vendor/courier/build")
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

</head>
<body>
<div class="navbar" id="navbar">
    <script>
        function redirectToLogout() {
            window.location.href = '/logout'
        }
        function redirecToProfile() {
            window.location.href = '/account'
        }
        function redirectToSettings() {
            window.location.href = '/account/settings'
        }
    </script>
    <ul>
        <li style="float: left"> <sl-icon-button name="list" label="Menu" style="font-size: 36px" onclick="opendrawer()"></sl-icon-button> </li>
        <li style="float: right; margin: 5px">
            <sl-dropdown>
                <sl-avatar slot="trigger" label="avatar"></sl-avatar>
                <sl-menu>
                    <!-- TODO: Add code to adjust based on if user is signed in -->
                    <sl-menu-item onclick="redirecToProfile()">Change profile</sl-menu-item>
                    <sl-menu-item onclick="redirectToSettings()">Account Settings</sl-menu-item>
                    <sl-menu-item onclick="redirectToLogout()">Logout</sl-menu-item>
                </sl-menu>
            </sl-dropdown>
        </li>
    </ul>
</div>
<div style="height: 94vh; display: flex; justify-content: center; align-items: center; top: 0">
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
</body>
<footer>
    <a href="https://github.com/CodifiedDev" style=" color: inherit; text-decoration: none; position: absolute;"><p>Made with 💖 in Australia 🇦🇺</p></a>
</footer>
<sl-drawer id="menudrawer" label="Menu" placement="start">
    <ul style="list-style-type: none; margin: 0; padding: 0;" class="menulist">
        <li><a href="/">Home</a></li>
        <li><a href="/dashboard">Dashboard</a></li>
        <li><a href="/library">Browse</a></li>
    </ul>
</sl-drawer>
<script>
    const drawer = document.getElementById('menudrawer')
    function opendrawer() {
        console.log('open')
        drawer.show()
    }
</script>

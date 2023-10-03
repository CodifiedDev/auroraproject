<!DOCTYPE html>
<html lang="en" class="sl-theme-dark">
<head>
    <title>@yield('title')</title>
    @vite("resources/js/app.js", "vendor/courier/build")
    @yield('head')
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
                    <sl-menu-item>Notifications <sl-badge slot="suffix" variant="neutral" pill pulse>0</sl-badge> </sl-menu-item>
                </sl-menu>
            </sl-dropdown>
        </li>
    </ul>
</div>
@yield('content')
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
</html>

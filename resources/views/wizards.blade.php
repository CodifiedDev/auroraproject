<!DOCTYPE html>
<html lang="en" class="sl-theme-dark">
<head>
    <title>Aurora - @yield('title')</title>
    @vite("resources/js/app.js", "vendor/courier/build")
@yield('head')
</head>
<body>
<sl-alert variant="warning" closable <?php if (\Illuminate\Support\Facades\Session::get('error') != null) {echo 'open';} ?> duration="3000">
    <sl-icon slot="icon" name="exclamation-triangle"></sl-icon>
    <?php if (\Illuminate\Support\Facades\Session::get('error') != null) {echo \Illuminate\Support\Facades\Session::get('error');}?>
</sl-alert>
<sl-alert variant="success" closable <?php if (\Illuminate\Support\Facades\Session::get('success') != null) {echo 'open';} ?> duration="3000">
    <sl-icon slot="icon" name="check-circle"></sl-icon>
    <?php if (\Illuminate\Support\Facades\Session::get('success') != null) {echo \Illuminate\Support\Facades\Session::get('success');}?>
</sl-alert>

<div style="height: 95vh; display: flex; justify-content: center; align-items: center; top: 0">
    <sl-card style="max-width: 30vw; min-width: 20vw; max-height: 70vh; min-height: 30vh;@yield('extrastyle')">
        <div style="text-align: center;">
            <h2>@yield('title')</h2>
        </div>
        @yield('content')
    </sl-card>
</div>
</body>
</html>

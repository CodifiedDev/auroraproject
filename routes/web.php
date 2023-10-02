<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
function verifySignedIn(){
    if(session('user') == null){
        if (isset($_COOKIE['rememberMe'])) {
            $userdatabase = DB::table('users')->where('remember_token', $_COOKIE['rememberMe'])->first();
            if ($userdatabase == null) {
                //return redirect('/login')->with('error', 'You must be logged in to do that');
                return false;
            }
            else {
                $userid = $userdatabase->id;
                $newToken = bin2hex(random_bytes(32));
                DB::table('authenticatedSessions')->insert([
                    'userid' => $userid,
                    'loginToken' => $newToken,
                    'loginTime' => date('U'),
                    'userAgent' => $_SERVER['HTTP_USER_AGENT']
                ]);
                session(['user' => $newToken]);
                return true;
            }
        }
        else {
            //return redirect('/login')->with('error', 'You must be logged in to do that');
            return false;
        }
    }
    $tokenDB = DB::table('authenticatedSessions')->where('loginToken', session('user'))->first();
    if ($tokenDB == null){
        //return redirect('/login')->with('error', 'You must be logged in to do that');
        return false;
    }
    if ($tokenDB->userAgent != $_SERVER['HTTP_USER_AGENT']){
        //return redirect('/login')->with('error', 'You must be logged in to do that');
        return false;
    }
    if ($tokenDB->loginTime > (int)date('U') + 2592000){
        //return redirect('/login')->with('error', 'Your session has expired, please login again');
        return false;
    }
    return true;
};

Route::get('/', function () {
    return view('home');
});
Route::get('/login', function () {
    if (verifySignedIn()){
        return redirect('/account')->with('error', 'You are already logged in');
    }
    return view('login');
});
Route::get('/register', function () {
    if (verifySignedIn()){
        return redirect('/account')->with('error', 'You are already logged in');
    }
    return view('register');
});
Route::post('/register', function (Request $request){
    //TODO: Setup email verification
    $name = $request->input('username');
    $email = $request->input('email');
    $password = $request->input('password');
    $dob = $request->input('dob');
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $time = date('U');
    DB::table('users')->insert([
        'name' => $name,
        'email' => $email,
        'password' => $hashedPassword,
        'DOB' => $dob,
        'created_at' => $time,
        'updated_at' => $time
    ]);
    //Verify email here
    return redirect('/login')->with('error', 'Account created successfully, please login');
});
Route::post('/login', function (Request $request){
    $email = $request->input('email');
    $password = $request->input('password');
    $user = DB::table('users')->where('email', $email)->first();
    if($user == null){
        return redirect('/login')->with('error', 'Account does not exist');
    }
    if(!password_verify($password, $user->password)){
        return redirect('/login')->with('error', 'Credentials do not match');
    }
    $newToken = bin2hex(random_bytes(32));
    DB::table('authenticatedSessions')->insert([
        'userid' => $user->id,
        'loginToken' => $newToken,
        'loginTime' => date('U'),
        'userAgent' => $_SERVER['HTTP_USER_AGENT']
    ]);
    session(['user' => $newToken]);
    return redirect('/account')->with('success', 'Logged in successfully');
});
Route::get('/account', function () {
    if (!verifySignedIn()){
        return redirect('/login')->with('error', 'You must be logged in to do that');
    }
    return view('account');
});
Route::get('/logout', function () {
    if (!verifySignedIn()){
        return redirect('/login')->with('error', 'You must be logged in to do that');
    }
    DB::table('authenticatedSessions')->where('loginToken', session('user'))->delete();
    session()->forget('user');
    return redirect('/login')->with('success', 'Logged out successfully');
});
Route::get('/dashboard', function (Request $request) {
    if (!verifySignedIn()){
        return redirect('/login')->with('error', 'You must be logged in to do that');
    }
    if (!isset($_COOKIE['profile'])) {
        $profileset = $request->input('profile');
        if ($profileset == null) {
            return redirect('/account');
        }
        else {
            if ($profileset == 4) {
                setcookie('profile', 1, time() + (86400 * 30), "/");
                return view('dashboard');
            }
            $userid = DB::table('authenticatedSessions')->where('loginToken', session('user'))->first()->userid;
            $profile = DB::table('profiles')->where('userid', $userid)->where('profileType', $profileset)->first();
            if ($profile == null) {
                return redirect('/dashboard/createprofile?profile=' . $profileset);
            }
            else {
                setcookie('profile', $profile->profileid, time() + (86400 * 30), "/");
                return view('dashboard');
            }
        }
    }
    return view('dashboard');
});
Route::get('/dashboard/createprofile', function (Request $request){
    if (!verifySignedIn()){
        return redirect('/login')->with('error', 'You must be logged in to do that');
    }
    $profileset = $request->input('profile');
    if ($profileset == null){
        return redirect('/account');
    }
    return view('newProfile');
});

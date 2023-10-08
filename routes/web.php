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
    session()->forget('profile');
    return redirect('/login')->with('success', 'Logged out successfully');
});
Route::get('/dashboard', function (Request $request) {
    if (!verifySignedIn()){
        return redirect('/login')->with('error', 'You must be logged in to do that');
    }
    if ($request->input('profile') != null){
        session(['profile' => $request->input('profile')]);
        return redirect('/dashboard');
    }
    if (session('profile') == null){
        return redirect('/account');}
        else {
            if (session('profile') == 4) {
                return view('dashboard');
            }
            $userid = DB::table('authenticatedSessions')->where('loginToken', session('user'))->first()->userid;
            $profile = DB::table('profiles')->where('userid', $userid)->where('profileType', session('profile'))->first();
            if ($profile == null) {
                return redirect('/dashboard/createprofile?profile=' . session('profile'));
            }
            else {
                return view('dashboard')->with('userid', $userid)->with('profile', $profile);
            }
        }
});
Route::get('/dashboard/createprofile', function (Request $request){
    if (!verifySignedIn()){
        return redirect('/login')->with('error', 'You must be logged in to do that');
    }
    $profileset = $request->input('profile');
    $userid = DB::table('authenticatedSessions')->where('loginToken', session('user'))->first()->userid;
    if ($profileset == null){
        return redirect('/account');
    }
    if (DB::table('profiles')->where('userid', $userid)->where('profileType', $profileset)->first() != null){
        return redirect('/dashboard?profile=' . $profileset);
    }
    return view('newProfile');
});
Route::post('/dashboard/createprofile', function (Request $request) {
    $userid = DB::table('authenticatedSessions')->where('loginToken', session('user'))->first()->userid;
    $profileset = $request->input('profile');
    $profileName = $request->input('username');
    $profilelocation = $request->input('location');
    $tablenotcreate = true;
    while ($tablenotcreate) {
        try {
            DB::table('profiles')->insert([
                'profileID' => bin2hex(random_bytes(32)),
                'userid' => $userid,
                'profileType' => $profileset,
                'username' => $profileName,
                'location' => $profilelocation,
            ]);
            $tablenotcreate = false;
        } catch (Exception $e) {
            continue;
        }
    }
    return redirect('/dashboard?profile=' . $profileset);
});
Route::get('/library', function (Request $request){
    if (!verifySignedIn()){
        return redirect('/login')->with('error', 'You must be logged in to do that');
    }
    $userid = DB::table('authenticatedSessions')->where('loginToken', session('user'))->first()->userid;
    $profileset = session('profile');
    if ($profileset == null){
        return redirect('/account');
    }
    $profile = DB::table('profiles')->where('userid', $userid)->where('profileType', $profileset)->first();
    if ($profile == null){
        return redirect('/dashboard/createprofile?profile=' . $profileset);
    }
    return view('library')->with('profile', $profile);
});
Route::get('/dashboard/compose', function (Request $request) {
    if (!verifySignedIn()){
        return redirect('/login')->with('error', 'You must be logged in to do that');
    }
    $userid = DB::table('authenticatedSessions')->where('loginToken', session('user'))->first()->userid;
    $profileset = session('profile');
    if ($profileset == null){
        return redirect('/account');
    }
    $profile = DB::table('profiles')->where('userid', $userid)->where('profileType', $profileset)->first();
    if ($profile == null){
        return redirect('/dashboard/createprofile?profile=' . $profileset);
    }
    if ($request->input('titleid')== null) {

        return redirect('/dashboard/compose/newTitle');
    }
    if ($request->input('page') == null){
        $highestpage = (int)DB::table('writtenContent')->where('titleid', $request->input('titleid'))->max('pageNumber') + 1;
        return redirect('/dashboard/compose?titleid=' . $request->input('titleid') . '&page=' . $highestpage);
    }
    if ($request->input('page') < 1){
        return redirect('/dashboard/compose?titleid=' . $request->input('titleid') . '&page=1');
    }
    if (DB::table('writtenContent')->where('titleid', $request->input('titleid'))->where('authorid', $profile->profileID)->first() == null){
        return redirect('/dashboard/compose');
    }
    if (DB::table('writtenContentPages')->where('titleID', $request->input('titleid'))->where('pageNumber', $request->input('page'))->first() == null){
        $highestpage = DB::table('writtenContentPages')->where('titleID', $request->input('titleid'))->max('pageNumber');
        DB::table('writtenContentPages')->insert([
            'titleid' => $request->input('titleid'),
            'pageNumber' => $highestpage + 1,
            'timestamp' => date('U'),
        ]);
        return redirect('/dashboard/compose?titleid=' . $request->input('titleid') . '&page=' . ($highestpage + 1));
    }
    return view('compose')->with('titleID', $request->input('titleid'))->with('page', $request->input('page'));
});
Route::get('/dashboard/compose/newTitle', function (Request $request){
    if (!verifySignedIn()){
        return redirect('/login')->with('error', 'You must be logged in to do that');
    }
    $userid = DB::table('authenticatedSessions')->where('loginToken', session('user'))->first()->userid;
    $profileset = session('profile');
    if ($profileset == null){
        return redirect('/account');
    }
    $profile = DB::table('profiles')->where('userid', $userid)->where('profileType', $profileset)->first();
    if ($profile == null){
        return redirect('/dashboard/createprofile?profile=' . $profileset);
    }
    return view('newTitle');
});
Route::post('/dashboard/compose/newTitle', function (Request $request){
   $newtitleid = bin2hex(random_bytes(32));
    $userid = DB::table('authenticatedSessions')->where('loginToken', session('user'))->first()->userid;
    $profileset = session('profile');
    $profileid = DB::table('profiles')->where('userid', $userid)->where('profileType', $profileset)->first()->profileID;
    $title = $request->input('title');
    $description = $request->input('description');
    $tags = $request->input('tags');
    DB::table('writtenContent')->insert([
        'titleID' => $newtitleid,
        'category' => $profileset,
        'authorid' => $profileid,
        'title' => $title,
        'miniDescription' => $description,
        'tags' => $tags,
        'visability' => 0,
    ]);
    return redirect('/dashboard/compose?titleid=' . $newtitleid);
});

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use LdapRecord\Models\ActiveDirectory\Group;
use LdapRecord\Models\ActiveDirectory\User;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\JsonResponse;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username(){
        return 'username';
    }

    protected function credentials(Request $request)
    {
        return [
            // 'uid' => $request->username,
            'samaccountname' => $request->username,
            'password' => $request->password
        ];
    }

    protected function authenticated(Request $request, $user)
    {
        
        $email = Auth::user()->email;
        $user = User::findByOrFail('mail', $email);

        $reportesE= Group::find('CN=Reportes_E,CN=Users,DC=argentina,DC=FSM,DC=CORP');
        $reportesL = Group::find('CN=Reportes_L,CN=Users,DC=argentina,DC=FSM,DC=CORP');
        $reportesA = Group::find('CN=Reportes_A,CN=Users,DC=argentina,DC=FSM,DC=CORP');

        if ($user->groups()->exists($reportesE)) {
            Auth::user()->syncRoles('reportes_E');
        }
        if ($user->groups()->exists($reportesL)) {
            Auth::user()->syncRoles('reportes_L');
        }
        if ($user->groups()->exists($reportesA)) {
            Auth::user()->syncRoles('Admin');
        }
        
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        $request->session()->flush();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect('/');
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Model\DimUsuariosCidadesPontos;
use App\Model\DimUsuariosLogsAcessos;
use App\Providers\RouteServiceProvider;
use App\User;
use Carbon\Traits\Timestamp;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Throwable;

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
    protected $redirectTo   = RouteServiceProvider::HOME;

    protected $maxAttempts  = 3; // Amount of bad attempts user can make

    protected $decayMinutes = 5;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function authenticated(Request $request, $user)
    {
        DimUsuariosLogsAcessos::create([
            'EMAIL'     => $user->email,
            'DATA_HORA' => Date('Y-m-d H:i:s'),
            'STATUS'    => 1,
            'IP'        => $_SERVER['REMOTE_ADDR']
        ]);
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        try {
            DimUsuariosLogsAcessos::create([
                'EMAIL'     => $request->email,
                'DATA_HORA' => Date('Y-m-d H:i:s'),
                'STATUS'    => 0,
                'IP'        => $_SERVER['REMOTE_ADDR']
            ]);
        } catch (Throwable $th) {
            throw $th;
        }

        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);
    }

    public function login(Request $request)
    {
        //$this->validateLogin($request);
        $request->validate([
            $this->username()      => 'required|string',
            'password'             => 'required|string',
            'g-recaptcha-response' => 'required|captcha',
        ]);


        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        /***********************************************************************************
        / CUSTOMIZED
        /**********************************************************************************/

        // VERIFICA SE O USUÁRIO ESTÁ ATIVO
        $user = User::where('email', $request->email)->first();
        if ( $user && !$user->ativo ) {
            return $this->sendFailedLoginResponse($request);
        }

        // VERIFICA CAPTCHA
        /*
        $validator = Validator::make( $request, [
            'g-recaptcha-response' => ['required', 'captcha']
        ]);
        if( $request->email == 'felipe.resilva@gmail.com' ){
            //dd( $request );
        }
        */
        /**********************************************************************************/

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

}
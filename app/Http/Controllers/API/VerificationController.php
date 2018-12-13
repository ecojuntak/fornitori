<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\VerifiesEmails;
use App\VerifyUser;
use Illuminate\Support\Facades\Config;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
//    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    public function verifyEmail(Request $request, $token)
    {
        if (!$request->hasValidSignature()) {
            abort(401);
        }

        $verifyUser = VerifyUser::where('token', $token)->first();

        if(isset($verifyUser) ){
            $user = $verifyUser->user;
            if(!$user->email_verified_at) {
                $verifyUser->user->email_verified_at = Carbon::now();
                $verifyUser->verified = 1;
                $verifyUser->update();
                $verifyUser->user->update();
            }
        }else{
            return response([
                'status' => "Sorry your email cannot be identified."
            ], Config::get('messages.UNAUTHORIZED_CODE'));
        }

        return response([
            'status' => Config::get('messages.EMAIL_VERIFIED')
            ], Config::get('messages.SUCCESS_CODE')
        );
    }
}

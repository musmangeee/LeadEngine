<?php

namespace App\Http\Controllers;

use App\Http\Requests\Web\ForgotPasswordRequest;
use App\Http\Requests\Web\LoginRequest;
use App\Http\Requests\Web\ResetPasswordRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\MessageBag;
use Lcobucci\JWT\Parser;

class AuthenticationController extends Controller
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function login()
    {
        return view('login');
    }

    public function doLogin(LoginRequest $request, MessageBag $errors)
    {
        $validated = $request->validated();
        $user = User::where('email', $validated['email'])->first();
        if(empty($user)){
            $errors->add('combination', 'Invalid email or password.');
            return redirect()->route('login')->withErrors($errors);
        }

        if($user->status == User::STATUS_DISABLED){
            $errors->add('combination', 'Your account has been disabled.');
            return redirect()->route('login')->withErrors($errors);
        }

        if (auth()->attempt($validated)) {
            return redirect()->to('dashboard');
        } else {
            $errors->add('combination', 'Invalid email or password.');
            return redirect()->route('login')->withErrors($errors);
        }
    }

    public function logout(Request $request)
    {
        $value = $request->bearerToken();
        if ($value) {
            $id = (new Parser())->parse($value)->getHeader('jti');
            $revoked = DB::table('oauth_access_tokens')->where('id', '=', $id)->update(['revoked' => 1]);
        }
        Auth::logout();
        return Response(['message' => 'You are successfully logged out'], 200);
    }

    public function forgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function doForgotPassword(ForgotPasswordRequest $request)
    {
        $validated = $request->validated();
        $credentials = ['email' => $request->email];
        $response = Password::sendResetLink($credentials, function (Message $message) {
            $message->subject($this->getEmailSubject());
        });
        switch ($response) {
            case Password::RESET_LINK_SENT:
                # code...
                return redirect()->route('password.forgot')->with('success-message', 'Reset password link sent to your email.');
            case Password::INVALID_USER:
                return redirect()->route('password.forgot')->with('error-message', 'Please input a valid email.');
        }
    }

    public function resetPassword(Request $request)
    {
        $token = $request->get('token');
        $email = $request->get('email');
        return view('auth.reset-password', compact('token', 'email'));
    }

    public function doResetPassword(ResetPasswordRequest $request)
    {
        $validated = $request->validated();

        $credentials = array_only($validated, ['email','token']);
        $user = $this->broker()->getUser($credentials);
        if (empty($user)) {
            return redirect()->route('password.reset', [
                'email' => $validated['email'],
                'token' => $validated['token']
            ])->with('error-message', 'The password reset token is invalid. Please try again.');
        }
    
        if (!$this->broker()->tokenExists($user, $credentials['token'])) {
            return redirect()->route('password.reset', [
                'email' => $validated['email'],
                'token' => $validated['token']
            ])->with('error-message', 'The password reset token is invalid. Please try again.');
        }

        //set the new password
        $user->password = Hash::make($validated['password']);
        $user->save();
        return redirect()->route('login')->with('success-message', 'Change password success.');
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker()
    {
        return Password::broker();
    }
}

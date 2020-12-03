<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ChangePasswordRequest;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

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
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Authentication passed...
            $user = Auth::getUser();
            if ($user->status != 'active') {
                return response()->json(['message' => 'Account is not active!'], Response::HTTP_UNAUTHORIZED);
            }
            $token = $user->createToken(null);

            $permissions = permissionToCaslRules($user->getAllPermissions());
            $roles = $user->getRoleNames();
            $options = $user->options;

            return response()->json([
                'options' => $options,
                'roles' => $roles,
                'permissions' => $permissions,
                'token' => $token->accessToken,
            ], 200);
        } else {
            return response()->json(['message' => 'Invalid email or password'], Response::HTTP_UNAUTHORIZED);
        }
    }

    public function logout(Request $request)
    {
        auth()->guard('web')->logout();
        $request->session()->flush();
        $request->session()->regenerate();
        return response()->json('Successfully logout', 200);
    }

    public function passwordReset(Request $request)
    {
        $credentials = ['email' => $request->email];
        $response = Password::sendResetLink($credentials, function (Message $message) {
            $message->subject($this->getEmailSubject());
        });

        switch ($response) {
            case Password::RESET_LINK_SENT:
                return response()->json(['status' => trans($response)]);
            case Password::INVALID_USER:
                return response()->json(['email' => trans($response)]);
        }
    }

    public function passwordChange(ChangePasswordRequest $request)
    {
        $validated = $request->validated();
        $email = decrypt($validated['token']);
        $exUser = User::where('email', $email)->firstOrFail();
        //if the user with old password found
        if (!empty($exUser)) {
            //set the new password
            $exUser->password = Hash::make($validated['password']);
            $exUser->save();
            return $exUser;
        }
    }
}

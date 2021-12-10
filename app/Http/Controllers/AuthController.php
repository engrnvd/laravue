<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponse;
use App\User;
use App\UserToken;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use ApiResponse;

    public function verifyToken()
    {
        return JWTAuth::parseToken()->authenticate();
    }

    public function login(Request $request)
    {
        $email = $request->get('email');
        $password = $request->get('password');

        $user = User::whereEmail($email)->first();
        if (!$user) {
            return $this->response('Unknown User', 401);
        }
        if (!$user->hasVerifiedEmail()) {
            return $this->response("Unauthorized, your email address {$user->email} is not verified.", 403);
        }

        if (!Hash::check($password, $user->password)) {
            return $this->response('Invalid credentials', 401);
        }

        return $this->logUserIn($user);
    }

    public function logUserIn($user)
    {
        Auth::login($user);

        $token = JWTAuth::fromUser($user);
        UserToken::firstOrCreate([
            'token' => $token,
            'user_id' => $user->getKey()
        ]);
        return $this->response([
            'token' => $token,
            'user' => $user,
        ]);
    }

    public function logout(Request $request)
    {
        try {
            // invalidate login token
            $token = preg_replace('/^Bearer /', '', $request->header('Authorization'));
            UserToken::where([
                ['user_id', '=', Auth::user()->getKey()],
                ['token', '=', $token],
            ])->delete();
            JWTAuth::invalidate(new \Tymon\JWTAuth\Token($token));

            return $this->response('User logged out successfully');
        } catch (\Exception $exception) {
            return $this->response('Sorry, the user cannot be logged out: ' . $exception->getMessage(), 400);
        }
    }

    /**
     * Verify an email using email token and set password.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function emailVerify(Request $request)
    {
        $this->validate($request, [
            'password' => 'required|string|confirmed',
            'token' => 'required|string',
        ]);
        /* @var $user User */
        $user = User::where('verification_token', $request->get('token'))->first();
        if (!$user) {
            return $this->response('Invalid token', 401);
        }

        if ($user->hasVerifiedEmail()) {
            return $this->response('Email address ' . $user->getEmailForVerification() . ' is already verified.');
        }
        $user->markEmailAsVerified();
        $user->unset('verification_token');
        $user->update(['password' => bcrypt($request->get('password')), "signature" => $request->signature]);

        return $this->response("{$user->email} successfully verified.");
    }
}

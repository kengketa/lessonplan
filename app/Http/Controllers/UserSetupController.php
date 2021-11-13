<?php

namespace App\Http\Controllers;

use Hash;
use App\Http\Requests\ResetPasswordRequest;
use App\Models\User;
use Illuminate\Support\Facades\Password;
use Inertia\Inertia;
use Inertia\Response;
use Session;
use Illuminate\Support\Facades\DB;
use App\Actions\SavePasswordUserSetupAction;
use Illuminate\Http\Request;

class UserSetupController extends Controller
{
    public function setup($token, $key): Response
    {
        $resetToken = DB::table('user_setups')->where([["user_id", $key], ['token', $token]])->first();
        if ($resetToken) {
            return Inertia::render("Users/SetupForm", ["resetModel" => $resetToken]);
        }
        abort(400, "Token or user is invalid");
    }

    public function doSetup(ResetPasswordRequest $request, SavePasswordUserSetupAction $savePasswordUserSetupAction)
    {
        $savePasswordUserSetupAction->execute($request->validated());
        return redirect()->route("login")->with('success', "Success, Your new password has been save!");
    }

    public function sentResetPassword(Request $request)
    {
        try {
            $user = User::where('id', $request->user)->first();
            $broker = Password::broker(config('fortify.passwords'));
            $broker->sendResetLink(['email' => $user->email]);

            return redirect()->back()->with('success', 'Successfully send password reset email.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Fail to send password reset email. '.$e->getMessage());
        }
    }
}

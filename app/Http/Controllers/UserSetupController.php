<?php

namespace App\Http\Controllers;

use Hash;
use App\Http\Requests\ResetPasswordRequest;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use Session;
use Illuminate\Support\Facades\DB;
use App\Actions\SavePasswordUserSetupAction;

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
}

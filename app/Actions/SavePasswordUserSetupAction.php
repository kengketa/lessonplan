<?php

namespace App\Actions;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SavePasswordUserSetupAction
{
    public function execute(array $data)
    {
        DB::table("user_setups")->where("user_id", $data['user_id'])->delete();
        $user = User::where("id", $data['user_id'])->first();
        $user->password = Hash::make($data['password']);
        $user->save();
    }
}

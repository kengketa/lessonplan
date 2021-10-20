<?php

namespace App\Actions;

use App\Models\User;
use App\Notifications\WelcomeEmailNotification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class SetupUserAction
{
    protected User $user;

    public function execute(User $user, $token)
    {
        $this->user = $user;
        DB::table("user_setups")->where("user_id", $this->user->id)->delete();
        DB::table('user_setups')->insert([
            'user_id' => $this->user->id,
            'token' => $token,
            'created_at' => new Carbon(),
        ]);

        $mailSetup = new WelcomeEmailNotification($this->user, $token);

        $this->user->notify($mailSetup);
    }
}

<?php

namespace App\Actions;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Str;

class SaveUserAction
{
    protected User $user;

    public function execute(User $user, array $data): User
    {
        $this->user = $user;

        if (! empty($this->user->id)) {
            $this->user->roles()->detach();
            $this->user->assignRole(Role::find($data["role"]));
            $this->user->update($data);

            return $this->user;
        }

        //create
        $data["password"] = $this->generateRandomString(50);
        $this->user = $this->user->create($data);
        $this->user->assignRole(Role::find($data["role"]));
        $setupUserAction = new SetupUserAction();
        $setupUserAction->execute($this->user, $this->generateToken());

        return $this->user;
    }

    protected function generateToken()
    {
        $key = config('app.key');

        if (Str::startsWith($key, 'base64:')) {
            $key = base64_decode(substr($key, 7));
        }

        $token = hash_hmac('sha256', Str::random(40), $key);

        return $token;
    }

    protected static function generateRandomString($length = 10)
    {
        return substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length / strlen($x)))), 1, $length);
    }
}

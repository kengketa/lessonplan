<?php

namespace App\Actions;

use App\Models\Role;
use App\Models\SchoolTeacher;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class SaveUserAction
{
    protected User $user;

    public function execute(User $user, array $data): User
    {
        $this->user = $user;
        if (!empty($this->user->id)) {
            $this->user->roles()->detach();
            $this->user->assignRole(Role::find($data["role"]));
            $this->user->update($data);
        }
        if (empty($this->user->id)) {
            //create
            $data["password"] = $this->generateRandomString(50);
            $this->user = $this->user->create($data);
            $this->user->assignRole(Role::find($data["role"]));
            $setupUserAction = new SetupUserAction();
            $setupUserAction->execute($this->user, $this->generateToken());
        }
        if ($data['school_id'] && $data["role"] >= 60 && $data["role"] <= 70) {
            $foundDuplicated = SchoolTeacher::where([
                "teacher_id" => $this->user->id
            ])->delete();
            SchoolTeacher::create([
                'school_id' => $data["school_id"],
                'teacher_id' => $this->user->id
            ]);
        }
        Cache::flush();
        return $this->user;
    }

    protected static function generateRandomString($length = 10)
    {
        return substr(
            str_shuffle(
                str_repeat(
                    $x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
                    ceil($length / strlen($x))
                )
            ),
            1,
            $length
        );
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

}

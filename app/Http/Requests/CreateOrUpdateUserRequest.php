<?php

namespace App\Http\Requests;

// use App\Enums\UserRoleEnum;
use app\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class CreateOrUpdateUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // base rules
        $rules = [
            "name" => ["required", "string", "max:255"],
            "role" => ["in:".User::ROLE_USER.",".User::ROLE_ADMIN.",".User::ROLE_SUPER_ADMIN.",".User::ROLE_TEACHER],
        ];

        if ($this->getMethod() === "POST") { //for create
            $rules["email"] = ["required", "email", "unique:users,email", "max:255"];
            // $rules["password"] = ["required", "min:6", "confirmed"];
        } else { //for update
            $user = $this->route("user"); //get param user
            $rules["email"] = ["required", "email", "unique:users,email,$user->id", "max:255"];
            // $rules["password"] = ["nullable", "min:6", "confirmed"];
        }

        return $rules;
    }
}

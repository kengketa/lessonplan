<?php

namespace App\Transformers;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    public function transform(User $user): array
    {
        $data = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'profile_photo_url' => $user->profile_photo_url,
            'role' => $user->present()->role,
            'created_at' => $user->present()->createdAt,
            'updated_at' => $user->present()->updatedAt,
        ];

        return $data;
    }
}

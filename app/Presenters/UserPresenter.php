<?php

namespace App\Presenters;

use App\Models\User;

class UserPresenter extends BasePresenter
{
    /**
     * @var User
     */
    protected $model;

    public function role(): string|null
    {
        $roles = $this->model->roles;
        if (count($roles) > 0) {
            return $roles[0]->name;
        }
        return null;
    }
}

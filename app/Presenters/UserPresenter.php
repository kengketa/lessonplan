<?php

namespace App\Presenters;

use App\Models\User;

class UserPresenter extends BasePresenter
{
    /**
     * @var User
     */
    protected $model;

    public function role(): string
    {
        $roles = $this->model->roles;
        if (count($roles) > 0) {
            return implode(', ', $roles->pluck('name')->map(function ($item) {
                return ucfirst(snakeCaseToText(strtolower($item)));
            })->all());
        }

        return '';
    }
}

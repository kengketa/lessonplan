<?php

namespace App\Presenters;

class BasePresenter extends Presenter
{
    public function createdAt()
    {
        $date = $this->model->created_at->timezone('Asia/Bangkok');
        if (app()->getLocale() === 'th') {
            return $date->locale('th')->isoFormat('Do MMMM ').((int) $date->format('Y') + 543).$date->format(' H:i');
        }

        return $date->format('j M Y H:i');
    }

    public function createdAtDateTime()
    {
        if ($this->model->created_at) {
            return $this->model->created_at->tz('Asia/Bangkok')->format('Y-m-d\TH:iP');
        }

        return '';
    }

    public function createdAtHuman()
    {
        if (! $this->model->created_at) {
            return '';
        }

        return $this->model->created_at->tz('Asia/Bangkok')->locale(app()->getLocale())->diffForHumans();
    }

    public function updatedAt()
    {
        $date = $this->model->updated_at->timezone('Asia/Bangkok');
        if (app()->getLocale() === 'th') {
            return $date->locale('th')->isoFormat('Do MMMM ').((int) $date->format('Y') + 543).$date->format(' H:i');
        }

        return $date->format('j M Y H:i');
    }

    public function updatedAtDateTime()
    {
        if ($this->model->updated_at) {
            return $this->model->updated_at->tz('Asia/Bangkok')->format('Y-m-d\TH:iP');
        }

        return '';
    }

    public function updatedAtHuman()
    {
        if (! $this->model->updated_at) {
            return '';
        }

        return $this->model->updated_at->tz('Asia/Bangkok')->locale(app()->getLocale())->diffForHumans();
    }

    public function deletedAt()
    {
        if ($this->model->deleted_at) {
            $date = $this->model->deleted_at->timezone('Asia/Bangkok');
            if (app()->getLocale() === 'th') {
                return $date->locale('th')->isoFormat('Do MMMM ').((int) $date->format('Y') + 543).$date->format(' H:i');
            }

            return $date->format('j M Y H:i');
        }

        return null;
    }

    public function deletedAtDateTime()
    {
        if ($this->model->deleted_at) {
            return $this->model->deleted_at->tz('Asia/Bangkok')->format('Y-m-d\TH:iP');
        }

        return '';
    }

    public function deletedAtHuman()
    {
        if (! $this->model->deleted_at) {
            return '';
        }

        return $this->model->deleted_at->tz('Asia/Bangkok')->locale(app()->getLocale())->diffForHumans();
    }
}

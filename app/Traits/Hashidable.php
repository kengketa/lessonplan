<?php

namespace App\Traits;

trait Hashidable
{
    public function getRouteKey()
    {
        return \Hashids::connection(get_called_class())->encode($this->getKey());
    }

    public function getHashidAttribute()
    {
        return \Hashids::connection(get_called_class())->encode($this->attributes['id']);
    }
}

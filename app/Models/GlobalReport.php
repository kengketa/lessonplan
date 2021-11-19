<?php

namespace App\Models;

use App\Traits\Hashidable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GlobalReport extends Model
{
    use HasFactory;
    use Hashidable;

    protected $fillable = ['creator_id', 'link'];
}

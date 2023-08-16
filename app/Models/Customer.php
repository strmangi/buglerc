<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;

    protected $table = 'customers';
    protected $dates = ['deleted_at'];
/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    
}

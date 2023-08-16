<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


use Sofa\Eloquence\Eloquence; // base trait
use Sofa\Eloquence\Mappable; // extension trait

class School extends Model
{

    protected $table = 'school';
    protected $dates = ['deleted_at'];
    protected $maps = [
		    'id' => 'SchoolID',
		];
/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    
}

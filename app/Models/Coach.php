<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coach extends Model
{
    protected $table = 'coaches';

/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function coach_types() {
    	return $this->belongsTo('CoachType');
  	}
}

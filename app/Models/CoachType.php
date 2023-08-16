<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoachType extends Model
{
    protected $table = 'coach_types';

	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function products() {
    	return $this->hasMany('Coach');
  	}
}

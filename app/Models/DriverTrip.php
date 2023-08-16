<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverTrip extends Model
{
    protected $table = 'coach_driver_trip';

	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
}

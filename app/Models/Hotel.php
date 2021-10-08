<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Hotel extends Model{

  use Notifiable;

  protected $fillable = [
    'name',
    'latitude',
    'longitude',
    'price',
    'country',
    'country_acronym',
    'state',
    'state_acronym'
  ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $table='videos';
    /*Relación uno a muchos*/
    public function comments(){
        return $this->hasMany('App\Models\Comentario')->orderBy('id', 'desc');
    }

    /*Relacion de muchos a uno*/
    public function user(){
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}

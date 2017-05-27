<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    //
    public function liker(){
    	return $this->belongsTo('App\User', 'user_id', 'id');
    }

    protected $fillable = [
    	'user_id', 'post_id'
    ];
}

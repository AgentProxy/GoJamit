<?php

namespace App;



use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    //

	public function user(){
		return $this->belongsTo('App\User','id');
	}

	public function likes(){
		return $this->hasMany('App\Like','post_id','id');
	}

	public function comments(){
		return $this->hasMany('App\Comment','post_id','id');
	}

    protected $fillable = [
    	'id','user_id', 'content', 'filename', 'plays'
    ];
}

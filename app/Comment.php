<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    //
    public function commenter(){
		return $this->belongsTo('App\User','user_id','id');
	}

	protected $fillable = [
    	'user_id', 'post_id','content'
    ];

}

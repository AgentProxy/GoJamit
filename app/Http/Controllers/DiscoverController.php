<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth;

class DiscoverController extends Controller
{
    //
    public function index($username){
    	$user = User::whereUsername($username)->first();
    	$user = User::findOrFail($user->id);
    }

    public function discover(Request $request){
        $user = User::findOrFail(Auth::user()->id);

        //$user->update(number_format($request->input('latitude'),8,'.',''));

        $age = $request->age_slider; 

        $user['latitude'] = (number_format($request->latitude,8,'.',''));
        $user['longitude'] = (number_format($request->longitude,8,'.',''));
        $user->update();
        $following_ids = array();
        foreach($user->following as $following_user){
               $following_ids[] = array(
                'id'=>$following_user->following->id,
             );
        }



       $follow_users = User::select()->whereNotIn('id',$following_ids)->get();
       // return view("discover.jammers", compact('follow_users'));
       return $request->age_slider;

    }

    public function solveDistance(){
        var R = 6371e3; // metres
        var t1 = $userLatitude.toRadians();
        // var φ2 = lat2.toRadians();
        var Δφ = (lat2-lat1).toRadians();
        //var Δλ = (lon2-lon1).toRadians();

        // var a = Math.sin(Δφ/2) * Math.sin(Δφ/2) +
        //         Math.cos(φ1) * Math.cos(φ2) *
        //         Math.sin(Δλ/2) * Math.sin(Δλ/2);
        // var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));

        // var d = R * c;
    }

}

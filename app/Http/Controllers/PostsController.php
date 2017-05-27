<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\User;
use App\Post;
use App\Follow;
use App\Like;
use App\Comment;
use Auth;
use Redirect;


class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($username)
    {
        //
        $user = User::whereUsername($username)->first();
        $user_id = $user->id;
        $following = App\Follow::where('follower_id', $user_id)
               ->get();
        
        return $following;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request,$username)
    {
        $user_id = Auth::user()->id;
        $post = new Post;
        $path = $request->audio;
        $post->content = $request->content;
        $post->user_id = $user_id;
        $post->filename = "0";
        $post->plays = 0;
        $post->save();

        if($request->hasFile('audio')){
            $filename = $request->audio->getClientOriginalName();
            $filename =  $username.$post->id.$filename;
            $filesize= $request->audio->getClientSize();

            $request->audio->move('user-audios',$filename);
             DB::table('posts')->where('id',$post->id)
            ->update(['filename' => $filename]);

        }

       //app('App\Http\Controllers\HomeController')->index();

      return redirect('home');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        //
       

        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    public function profile($username){
         $user = User::whereUsername($username)->first();
         $id = $user->id;
         $posts = Post::where('user_id',$id)
         ->orderBy('created_at','desc')
         ->get();

        $followed = Follow::where('following_id',$user->id)
        ->where('follower_id',Auth::user()->id);

        if($followed==""){
            $followed='false';
        }
        else{
            $followed ='true';
        }

        return view('user.posts',compact('user','posts','followed'));
    }

    public function delete($post_id){
        $id = $post_id;
        $deletedRows = Post::where('id', $post_id)
        ->delete();

        //FILE DELETION RESEARCH
        return $id;
    }

    public function play($post_id){
        $post = Post::where('id', $post_id)->first();
        $plays = $post->plays;
        
        if($post->user_id != Auth::user()->id){
            $plays = $post->plays;
            $plays++;
             DB::table('posts')
             ->where('id',$post->id)
             ->update(array('plays'=>$plays));
        }

        return $plays;
    }

    public function like($post_id){
        $post = Post::where('id', $post_id)->first();
        $like = new Like;
        $like->post_id = $post_id;
        $like->user_id = Auth::user()->id;
        $like->save();

        $likers = array();
        foreach($post->likes as $like){
            $likers[] = array(
                'username'=>$like->liker->username,
                'name'=>$like->liker->fname
                );
        }
        return $likers;
    }

    public function unlike($post_id){
        $like = new Like;
        Like::where('post_id', $post_id)
        ->where('user_id',Auth::user()->id)
        ->delete();

         $post = Post::where('id', $post_id)->first();

        $likers = array();
        foreach($post->likes as $like){
            $likers[] = array(
                'username'=>$like->liker->username,
                'name'=>$like->liker->fname
                );
        }
        return $likers;
    }

    public function comment($post_id,Request $request){
        $post = Post::where('id', $post_id)->first();
        $comment = new Comment;
        $comment->post_id = $post_id;
        $comment->user_id = Auth::user()->id;
        $comment->content = $request->content;
        $comment->save();

        $commenters = array();
        // foreach($post->comments as $comment){
        //     $commenters[] = array(
        //         'username'=>$comment->commenter->username,
        //         'name'=>$comment->commenter->fname
        //         );
        // }
         return redirect('home');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

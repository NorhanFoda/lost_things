<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\User;
use Illuminate\Support\Facades\DB;

class FavoritesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }


    public function addToFavorites(Request $request){
        $user_fav = User::find($request->user_id)->favorites;
        if(count($user_fav) > 0){
            foreach($user_fav as $fav)    {
                if($fav->post_id == $request->post_id){
                    return response()->json([
                        'error' => 'This post is added to favorites before by same user'
                    ], 400);
                }       
            }
        }
        $fav = Favorite::create(['post_id' => $request->post_id]);
        User::find($request->user_id)->favorites()->attach($fav->id);
        return response()->json([
            'data' => 'Post added to favorites'
        ], 201);
        // $this->validate($request, ['post_id' => 'required', 'user_id' => 'required']);
        // $fav_post = Favorite::where('post_id', $request->post_id)->first();
        // if($fav_post->user_id == $request->user_id){
        //     return response()->json([
        //         'error' => 'This post is added to favorites before by same user'
        //     ], 400);
        // }
        
        // $fav = Favorite::create(['post_id' => $request->post_id]);
        // User::find($request->user_id)->favorites()->attach($fav->id);
        // return response()->json([
        //     'data' => 'Post added to favorites'
        // ], 201);
        // return response()->json([
        //     'error' => 'Post already exists in favorites'
        // ], 400);
    }

    public function removeFromFavories(Request $request){

        $this->validate($request, ['post_id' => 'required', 'user_id' => 'required']);
        $fav = Favorite::where('post_id', $request->post_id)->first();
        if($fav){
            User::find($request->user_id)->favorites()->detach($fav->id);
            $fav->delete();
            return response()->json([
                'data' => 'Post removed from favorites'
            ], 200);
        }
        return response()->json([
            'error' => 'Post does not exist in favorites'
        ], 400);
    }
}

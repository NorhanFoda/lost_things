<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\User;

class FavoritesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }


    public function addToFavorites(Request $request){
        
        $this->validate($request, ['post_id' => 'required', 'user_id' => 'required']);
        if(!Favorite::where('post_id', $request->post_id)->first()){
            $fav = Favorite::create(['post_id' => $request->post_id]);
            User::find($request->user_id)->favorites()->attach($fav->id);
            return response()->json([
                'data' => 'Post added to favorites'
            ], 201);
        }
        return response()->json([
            'error' => 'Post already exists in favorites'
        ], 400);
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

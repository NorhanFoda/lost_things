<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\Block;

class BlockController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function getBlockList(User $user)
    {
        $this->checkUserAuthorization($user->id);
        return $user->blockList;
    }

    public function blockUser(Request $request){

        $this->checkUserAuthorization($request->user_id);
        $this->validate($request, ['user_id' => 'required', 'blocked_id' => 'required']);
        if(!Block::where('user_id', $request->user_id)->where('blocked_id', $request->blocked_id)->first()){
            Block::create(['user_id' => $request->user_id, 'blocked_id' => $request->blocked_id]);
            $user = User::find($request->blocked_id);
            $user->is_blocked = 1;
            $user->save();
            return response()->json([
                'data' => 'user is blocked'
            ], 201);
        }
        return response()->json([
            'error' => 'The blocked user is already blocked by same user befor'
        ], 400);
    }

    public function unblockUser(Request $request){

        $this->checkUserAuthorization($request->user_id);
        $this->validate($request, ['user_id' => 'required', 'blocked_id' => 'required']);
        $block = Block::where('user_id', $request->user_id)->where('blocked_id', $request->blocked_id)->first();
        if($block){
            $user = User::find($request->blocked_id);
            $user->is_blocked = 0;
            $user->save();
            $block->delete();
            return response()->json([
                'data' => 'user is unblocked'
            ], 201);
        }
        return response()->json([
            'error' => 'The blocked user does not exist in block list'
        ], 400);
    }

    public function  checkUserAuthorization($id){
        if(auth()->user()->id !== $id){
            return response()->json([
                'error' => 'User is UN AUTHORIZED to performe this action'
            ], 400);
        }
    }
}

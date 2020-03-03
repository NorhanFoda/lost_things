<?php

namespace App\Http\Controllers\dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use Auth;

class CommentController extends Controller
{
    public function __construct()
    {
        Auth::shouldUse('web');
    }
    public function deleteComment(Request $request){
        if($request->ajax()){
            Comment::find($request->id)->delete();
            session()->flash('success', trans('admin.comment_deleted'));
            return response()->json([
                'data' => 1,
            ], 200);
        }
    }
}

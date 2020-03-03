<?php

namespace App\Http\Controllers\dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\Post;
use Auth;

class AdminHomeController extends Controller
{
    public function __construct()
    {
        Auth::shouldUse('web');
    }
    public function index(){
        $posts = count(Post::all());
        $losts = count(Post::where('found', 0)->get());
        $founds = count(Post::where('found', 1)->get());
        $users = count(User::where('is_admin', 0)->get());
        $blocked_users = count(User::where('is_admin', 0)->where('is_blocked', 1)->get());
        return view('admin.index', compact('losts', 'founds', 'users', 'blocked_users', 'posts'));
    }
}

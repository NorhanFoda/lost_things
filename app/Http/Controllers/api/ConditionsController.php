<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Condition;

class ConditionsController extends Controller
{
    public function getConditions(){
        return response()->json([
            'data' => Condition::all()
        ], 200);
    }
}

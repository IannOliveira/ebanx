<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResetController extends Controller
{
    public function reset(){
        DB::table('accounts')->truncate();
        return response()->json([], 200);
    }

}

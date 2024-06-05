<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;

class BalanceController extends Controller
{
    public function getBalance(Request $request){
        $account = Account::find($request->query('account_id'));
        if(!$account) {
            return response()->json(0, 404);
        }

        return response()->json($account->balance, 200);
    }
}

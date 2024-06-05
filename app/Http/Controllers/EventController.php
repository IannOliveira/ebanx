<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function handleEvent(Request $request){
        $type = $request->input('type');
        switch ($type) {
            case 'deposit':
                return $this->deposit($request);
            case 'withdraw':
                return $this->withdraw($request);
            case 'transfer':
                return $this->transfer($request);
            default:
                return response()->json(['error' => 'Invalid event type'], 400);
        }
    }

    private function deposit(Request $request)
    {
        $destination = $request->input('destination');
        $amount = $request->input('amount');

        $account = Account::find($destination);
        if (!$account) {
            $account = Account::create(['id' => $destination, 'balance' => $amount]);
        } else {
            $account->balance += $amount;
            $account->save();
        }

        return response()->json(['destination' => ['id' => $destination, 'balance' => $account->balance]], 201);
    }

    private function withdraw(Request $request)
    {
        $origin = $request->input('origin');
        $amount = $request->input('amount');

        $account = Account::find($origin);
        if (!$account) {
            return response()->json(0, 404);
        }

        $account->balance -= $amount;
        $account->save();

        return response()->json(['origin' => ['id' => $origin, 'balance' => $account->balance]], 201);
    }

    private function transfer(Request $request)
    {
        $origin = $request->input('origin');
        $destination = $request->input('destination');
        $amount = $request->input('amount');

        $originAccount = Account::find($origin);
        if (!$originAccount) {
            return response()->json(0, 404);
        }

        $originAccount->balance -= $amount;
        $originAccount->save();

        $destinationAccount = Account::find($destination);
        if (!$destinationAccount) {
            $destinationAccount = Account::create(['id' => $destination, 'balance' => $amount]);
        } else {
            $destinationAccount->balance += $amount;
            $destinationAccount->save();
        }

        return response()->json([
            'origin' => ['id' => $origin, 'balance' => $originAccount->balance],
            'destination' => ['id' => $destination, 'balance' => $destinationAccount->balance]
        ], 201);
    }

}

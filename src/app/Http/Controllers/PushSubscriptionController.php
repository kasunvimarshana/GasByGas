<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PushSubscription;
use Exception;

class PushSubscriptionController extends Controller {
    public function store(Request $request) {
        try {
            $subscription = $request->user()->updatePushSubscription(
                $request->endpoint,
                $request->keys['p256dh'],
                $request->keys['auth']
            );

            return response()->json(['success' => true]);
        } catch (Exception $e){
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function destroy(Request $request) {
        try {
            $request->user()->deletePushSubscription($request->endpoint);

            return response()->json(['success' => true]);
        } catch (Exception $e){
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }
}

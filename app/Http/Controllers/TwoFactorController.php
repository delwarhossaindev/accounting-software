<?php

namespace App\Http\Controllers;

use App\Notifications\TwoFactorCode;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class TwoFactorController extends Controller
{
    public function showChallenge(Request $request)
    {
        $user = $request->user();
        if (!$user || !$user->two_factor_enabled) {
            return redirect()->route('dashboard');
        }

        if (!$user->two_factor_code || !$user->two_factor_expires_at || $user->two_factor_expires_at->isPast()) {
            $this->sendCode($user);
        }

        return view('auth.two-factor');
    }

    public function verify(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|string',
        ]);

        $user = $request->user();
        if (!$user->two_factor_code || !$user->two_factor_expires_at) {
            return back()->with('error', 'Code expired, resend a new one.');
        }
        if ($user->two_factor_expires_at->isPast()) {
            return back()->with('error', 'Code expired, resend a new one.');
        }
        if (!hash_equals((string)$user->two_factor_code, (string)$data['code'])) {
            return back()->with('error', 'Incorrect code.');
        }

        $user->forceFill([
            'two_factor_code' => null,
            'two_factor_expires_at' => null,
        ])->save();

        session(['two_factor_passed' => true]);

        return redirect()->intended(route('dashboard'));
    }

    public function resend(Request $request)
    {
        $this->sendCode($request->user());
        return back()->with('success', 'A new code has been sent to your email.');
    }

    public function toggle(Request $request)
    {
        $user = $request->user();
        $user->two_factor_enabled = !$user->two_factor_enabled;
        $user->two_factor_code = null;
        $user->two_factor_expires_at = null;
        $user->save();

        session()->forget('two_factor_passed');

        return back()->with('success', $user->two_factor_enabled
            ? '2FA enabled. You will need a code at next login.'
            : '2FA disabled.');
    }

    private function sendCode($user): void
    {
        $code = (string) random_int(100000, 999999);
        $user->forceFill([
            'two_factor_code' => $code,
            'two_factor_expires_at' => Carbon::now()->addMinutes(10),
        ])->save();

        try {
            $user->notify(new TwoFactorCode($code));
        } catch (\Throwable $e) {
            logger()->warning('Could not dispatch 2FA email: ' . $e->getMessage());
        }
    }
}

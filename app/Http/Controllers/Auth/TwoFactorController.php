<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FALaravel\Support\Authenticator;

class TwoFactorController extends Controller
{
    /**
     * Show the 2FA challenge view
     */
    public function challenge(Request $request)
    {
        if (!$request->session()->has('2fa:user:id')) {
            return redirect('/');
        }
        return view('auth.two-factor-challenge');
    }

    /**
     * Verify the code upon logging in
     */
    public function verifyChallenge(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $userId = $request->session()->get('2fa:user:id');
        if (!$userId) {
            return redirect()->route('login');
        }

        $user = \App\Models\User::find($userId);
        if (!$user) {
            return redirect()->route('login');
        }

        $google2fa = app('pragmarx.google2fa');
        $valid = $google2fa->verifyKey($user->two_factor_secret, $request->code);

        if ($valid) {
            $remember = $request->session()->get('2fa:remember', false);
            Auth::login($user, $remember);
            $user->last_login = now();
            $user->save();
            $request->session()->forget(['2fa:user:id', '2fa:remember']);
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors(['code' => 'The provided two-factor authentication code was invalid.']);
    }

    /**
     * Enable 2FA, return SVG QR Code
     */
    public function enable(Request $request)
    {
        $google2fa = app('pragmarx.google2fa');

        $secret = $google2fa->generateSecretKey();
        
        $request->session()->put('2fa_secret_setup', $secret);

        $qrCodeUrl = $google2fa->getQRCodeUrl(
            config('app.name'),
            $request->user()->email,
            $secret
        );
        
        $writer = new \BaconQrCode\Writer(new \BaconQrCode\Renderer\ImageRenderer(
            new \BaconQrCode\Renderer\RendererStyle\RendererStyle(200),
            new \BaconQrCode\Renderer\Image\SvgImageBackEnd()
        ));
        $qrCodeSvg = $writer->writeString($qrCodeUrl);

        return response()->json([
            'secret' => $secret,
            'qr_code' => $qrCodeSvg,
        ]);
    }

    /**
     * Confirm code and permanently store the secret
     */
    public function confirm(Request $request)
    {
        $request->validate([
            'code' => 'required',
        ]);

        $secret = $request->session()->get('2fa_secret_setup');
        if (!$secret) {
            return response()->json(['message' => 'Setup session expired.'], 400);
        }

        $google2fa = app('pragmarx.google2fa');
        $valid = $google2fa->verifyKey($secret, $request->code);

        if ($valid) {
            $user = $request->user();
            $user->two_factor_secret = $secret;
            $user->save();
            $request->session()->forget('2fa_secret_setup');
            return response()->json(['message' => 'Two-step verification enabled.']);
        }

        return response()->json(['message' => 'Invalid code.'], 400);
    }

    /**
     * Disable 2FA
     */
    public function disable(Request $request)
    {
        $user = $request->user();
        $user->two_factor_secret = null;
        $user->save();
        
        return response()->json(['message' => 'Two-step verification disabled.']);
    }
}

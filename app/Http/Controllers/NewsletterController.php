<?php

namespace App\Http\Controllers;

use App\Mail\NewsletterConfirmation;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
        ]);

        $sub = NewsletterSubscriber::subscribe($request->input('email'));

        if ($sub->wasRecentlyCreated) {
            // Gửi email xác nhận (fail silently — không block đăng ký)
            try {
                Mail::to($sub->email)->send(new NewsletterConfirmation($sub));
            } catch (\Throwable) {
                // Log nhưng không fail request
            }
            $message = 'Đăng ký thành công! Vui lòng kiểm tra email để xác nhận.';
        } elseif (!$sub->isConfirmed()) {
            // Gửi lại email xác nhận nếu chưa confirm
            try {
                Mail::to($sub->email)->send(new NewsletterConfirmation($sub));
            } catch (\Throwable) {}
            $message = 'Email chưa xác nhận. Chúng tôi đã gửi lại email xác nhận.';
        } else {
            $message = 'Email này đã đăng ký và xác nhận nhận tin từ VNKR rồi.';
        }

        return redirect()->back()->with('newsletter_success', $message);
    }

    /**
     * GET /newsletter/confirm/{token}
     */
    public function confirm(string $token)
    {
        $sub = NewsletterSubscriber::where('token', $token)->firstOrFail();

        if (!$sub->isConfirmed()) {
            $sub->update(['confirmed_at' => now()]);
            $message = 'Xác nhận thành công! Bạn sẽ nhận bản tin từ VNKR.';
        } else {
            $message = 'Email của bạn đã được xác nhận trước đó.';
        }

        return redirect()->route('index')->with('success', $message);
    }

    /**
     * GET /newsletter/unsubscribe/{token}
     */
    public function unsubscribe(string $token)
    {
        $sub = NewsletterSubscriber::where('token', $token)->firstOrFail();
        $sub->update(['unsubscribed_at' => now()]);

        return redirect()->route('index')
                         ->with('success', 'Bạn đã hủy đăng ký nhận tin từ VNKR.');
    }
}

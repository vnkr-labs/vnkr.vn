<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\Contact;

class ContactController extends Controller
{


    public function index(Request $request)
    {
        $query = Contact::query();


        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $contacts = $query->get();

        return view('admin.contact.index', compact('contacts'));
    }

    public function show()
    {
        return view('fe.contact');
    }

    public function submit(Request $request)
    {
        // Check if the user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login')->withErrors(['You must be logged in to submit a contact form.']);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string',
        ]);

        // Save the form data to the database with the authenticated user's ID
        Contact::create([
            'user_id' => auth()->id(),
            'name' => $validated['name'],
            'email' => $validated['email'],
            'message' => $validated['message'],
        ]);

        // // Optionally, you can send an email notification as well
        // Mail::send([], [], function ($message) use ($validated) {
        //     $message->to('your-email@example.com')
        //         ->subject('New Contact Form Submission')
        //         ->from($validated['email'], $validated['name'])
        //         ->setBody($validated['message'], 'text/plain');
        // });

        return back()->with('success', 'Thank you for contacting us!');
    }

    public function destroy(Contact $contact)
    {
        try {
            $contact->delete();
            return redirect()->route('contact.index')->with('success', 'Xóa Thành Công');

        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Xóa Thất Bại');
        }
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\Contact;
use App\Models\CmsPage;
use App\Http\Requests\ContactFormRequest;
use Exception;

class ContactController extends Controller
{
    public function show()
    {
        try {
            return view('contact');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Unable to load contact page: ' . $e->getMessage());
        }
    }
    
    public function submit(ContactFormRequest $request)
    {
        try {
            $validated = $request->validated();
            
            $contact = new Contact($validated);
            
            if (Auth::check()) {
                $contact->user_id = Auth::id();
            }
            
            $contact->save();
            
            return redirect()->back()->with('success', 'Thank you for your message. We will get back to you soon!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to submit your message: ' . $e->getMessage())->withInput();
        }
    }

    public function aboutus(){
        try {
            $data = CmsPage::where('slug','about-us')->where('status',1)->first();
            return view('about',compact('data'));
        } catch (Exception $e) {
            return redirect()->route('home')->with('error', 'Unable to load about page: ' . $e->getMessage());
        }
    }

    public function privacy(){
        try {
            $data = CmsPage::where('slug','privacy-policy')->where('status',1)->first();
            return view('about',compact('data'));
        } catch (Exception $e) {
            return redirect()->route('home')->with('error', 'Unable to load privacy policy page: ' . $e->getMessage());
        }
    }

    public function terms(){
        try {
            $data = CmsPage::where('slug','terms')->where('status',1)->first();
            return view('about',compact('data'));
        } catch (Exception $e) {
            return redirect()->route('home')->with('error', 'Unable to load terms page: ' . $e->getMessage());
        }
    }
}

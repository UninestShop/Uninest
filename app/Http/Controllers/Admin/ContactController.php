<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        try {
            $contacts = Contact::with('user')->latest()->paginate(15);
            return view('admin.contacts.index', compact('contacts'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error fetching contacts: ' . $e->getMessage());
        }
    }

    public function show(Contact $contact)
    {
        try {
            // Mark as read if pending
            if ($contact->status === 'pending') {
                $contact->status = 'read';
                $contact->save();
            }
            
            return view('admin.contacts.show', compact('contact'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error showing contact: ' . $e->getMessage());
        }
    }

    public function markAsResponded(Contact $contact)
    {
        try {
            $contact->status = 'responded';
            $contact->save();
            
            return redirect()->back()->with('success', 'Contact marked as responded');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error updating contact status: ' . $e->getMessage());
        }
    }
}

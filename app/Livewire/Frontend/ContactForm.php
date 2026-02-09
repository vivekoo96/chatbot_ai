<?php

namespace App\Livewire\Frontend;

use Livewire\Component;

class ContactForm extends Component
{
    public $name = '';
    public $email = '';
    public $subject = '';
    public $message = '';
    public $successMessage = '';

    protected $rules = [
        'name' => 'required|min:3',
        'email' => 'required|email',
        'message' => 'required|min:10',
    ];

    public function submit()
    {
        $this->validate();

        \App\Models\ContactMessage::create([
            'name' => $this->name,
            'email' => $this->email,
            'subject' => $this->subject ?: 'General Inquiry',
            'message' => $this->message,
        ]);

        $this->reset(['name', 'email', 'subject', 'message']);
        $this->successMessage = 'Thank you for your message! We will get back to you soon.';
    }

    public function render()
    {
        return view('livewire.frontend.contact-form');
    }
}

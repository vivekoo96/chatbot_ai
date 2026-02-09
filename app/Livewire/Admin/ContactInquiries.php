<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class ContactInquiries extends Component
{
    use \Livewire\WithPagination;

    public $search = '';
    public $status = '';

    public function markAsRead($id)
    {
        \App\Models\ContactMessage::findOrFail($id)->update(['status' => 'read']);
    }

    public function delete($id)
    {
        \App\Models\ContactMessage::findOrFail($id)->delete();
    }

    public function render()
    {
        $messages = \App\Models\ContactMessage::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
                    ->orWhere('subject', 'like', '%' . $this->search . '%');
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->latest()
            ->paginate(15);

        return view('livewire.admin.contact-inquiries', [
            'messages' => $messages,
        ])->layout('components.admin-layout');
    }
}

<?php

namespace App\Http\Livewire;

use Livewire\Component;

class WarningModal extends Component
{
    public $isOpen = false;
    public $title = '';
    public $message = '';

    protected $listeners = ['openModal' => 'show'];

    public function show($title, $message)
    {
        $this->title = $title;
        $this->message = $message;
        $this->isOpen = true;
    }

    public function close()
    {
        $this->isOpen = false;
    }

    public function render()
    {
        return view('livewire.warning-modal');
    }
}

<?php

namespace App\Http\Livewire;

use Livewire\Component;

class ConfirmationModal extends Component
{
    public $isOpen = false;
    public $message = '';
    public $callback;
    public $color;

    protected $listeners = ['showConfirmation'];
    public $callbackParams = [];

    public function showConfirmation($message, $color ,$callback, ...$params)
    {
        $this->message = $message;
        $this->callback = $callback;
        $this->callbackParams = $params;
        $this->isOpen = true;
        $this->color = $color;
        $this->dispatchBrowserEvent('modal-open');
    }

    public function confirm()
    {
        $this->emit($this->callback, ...$this->callbackParams);
        $this->closeModal();
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->message = '';
        $this->callback = '';
    }

    public function render()
    {
        return view('livewire.confirmation-modal');
    }
}

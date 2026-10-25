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

    public function showConfirmation($message, $color, $action, $reviewId = null)
    {
        $this->message = $message;
        $this->callback = $action;
        $this->callbackParams = $reviewId === null || $reviewId === '' ? [] : [$reviewId];
        $this->isOpen = true;
        $this->color = $color;
        $this->dispatch('modal-open');
    }

    public function confirm()
    {
        if ($this->callbackParams !== []) {
            $this->dispatch($this->callback, id: $this->callbackParams[0]);
        } else {
            $this->dispatch($this->callback);
        }
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

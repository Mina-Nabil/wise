<?php

namespace App\Traits;

use Illuminate\Validation\ValidationException;

trait AlertFrontEnd
{

    public function alert($type, $message)
    {
        $this->dispatchBrowserEvent('toastalert', [
            'message' => $message,
            'type' => $type, // or 'failed' or 'info'
        ]);
    }

    public function throwError($property, $message)
    {
        throw ValidationException::withMessages([
            $property => $message
        ]);
    }
}

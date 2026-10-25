<?php

namespace App\Traits;

use Illuminate\Validation\ValidationException;

trait AlertFrontEnd
{

    public function alert($type, $message)
    {
        $this->dispatch('toastalert', message: $message, type: $type);
    }

    public function throwError($property, $message)
    {
        throw ValidationException::withMessages([
            $property => $message
        ]);
    }
}

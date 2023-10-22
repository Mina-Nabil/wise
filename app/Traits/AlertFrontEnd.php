<?php

namespace App\Traits;


trait AlertFrontEnd
{

    public function alert($type, $message)
    {
        $this->dispatchBrowserEvent('toastalert', [
            'message' => $message,
            'type' => $type, // or 'failed' or 'info'
        ]);
    }
}

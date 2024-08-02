<?php

use Illuminate\Support\Facades\Facade;

return [
    'rules' => 'file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,bmp,gif,svg,webp|max:33000',
];
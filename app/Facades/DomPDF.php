<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class DomPDF extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'dompdf';
    }
}

<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Dompdf\Dompdf;
use Dompdf\Options;

class DomPDFServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('dompdf', function ($app) {
            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isPhpEnabled', true);

            $dompdf = new Dompdf($options);
            return $dompdf;
        });
    }

    public function boot()
    {
        //
    }
}

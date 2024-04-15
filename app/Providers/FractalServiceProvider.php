<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use League\Fractal\Manager;
use League\Fractal\Serializer\DataArraySerializer;
use App\Http\Response\FractalResponse; // Tambahkan impor ini

class FractalServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Bind the DataArraySerializer to an interface contract
        $this->app->bind(
            'League\Fractal\Serializer\SerializerAbstract',
            'League\Fractal\Serializer\DataArraySerializer'
        );

        // Bind FractalResponse to the service container
        $this->app->bind(FractalResponse::class, function ($app) {
            $manager = new Manager();
            $serializer = $app->make('League\Fractal\Serializer\SerializerAbstract');

            return new FractalResponse($manager, $serializer);
        });

        // Alias FractalResponse as 'fractal'
        $this->app->alias(FractalResponse::class, 'fractal');
    }

    public function boot()
    {
        // Logika yang perlu dijalankan setelah semua layanan terdaftar
    }
}

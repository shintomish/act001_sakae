<?php

namespace App\Providers;

use AllowNonRfcComplaintEmailValidator;
use Illuminate\Support\ServiceProvider;
use Swift;
use Swift_DependencyContainer;

class AllowNonRfcComplaintEmailPatchServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        Swift::init(
            function () {
                Swift_DependencyContainer::getInstance()
                    ->register('email.validator')
                    ->asSharedInstanceOf(AllowNonRfcComplaintEmailValidator::class);
            }
        );
    }
}
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use View;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer(['entrees.view','entrees.show','entrees.boite','emails.sending','home','boite','emails.inbox','envoyes','envoyes.view','envoyes.show','envoyes.brouillons','dossiers','dossiers.saving','dossiers.view','demo.test'],'App\Http\ViewComposers\varforallComposer');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        
    }
}

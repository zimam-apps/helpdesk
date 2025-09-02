<?php

namespace Workdo\Reports\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class ViewComposer extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */

    public function boot(){
        view()->composer(['admin.users.dashboard'], function ($view)
        {
            if(Auth::check() && Auth::user()->hasRole('agent'))
            {
                if(moduleIsActive('Reports'))
                {
                    $view->getFactory()->startPush('agent_report', view('reports::agent.dashboard'));
                }
            }

        });
    }
}

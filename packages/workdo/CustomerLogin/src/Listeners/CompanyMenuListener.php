<?php

namespace Workdo\CustomerLogin\Listeners;

use App\Events\CompanyMenuEvent;

class CompanyMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanyMenuEvent $event): void
    {
        $module = 'CustomerLogin';
        $menu = $event->menu;
        $menu->add([
            'title' => __('Create Ticket'),
            'icon' => 'ticket',
            'name' => 'createticket',
            'parent' => null,
            'order' => 10,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'create.ticket',
            'module' => $module,
            'permission' => 'customer ticket-create'
        ]);
    }
}

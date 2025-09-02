<?php

namespace Workdo\Reports\Listeners;

use App\Events\CompanyMenuEvent;

class CompanyMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanyMenuEvent $event): void
    {
        $module = 'Reports';
        $menu = $event->menu;
        $menu->add([
            'title' => __('Reports'),
            'icon' => 'chart-bar',
            'name' => 'reports',
            'parent' => null,
            'order' => 1305,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => 'reports manage'
        ]);

        $menu->add([
            'title' => __('Ticket Reports'),
            'icon' => '',
            'name' => 'ticket-reports',
            'parent' => 'reports',
            'order' => 5,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'reports.ticket',
            'module' => $module,
            'permission' => 'reports manage'
        ]);

        $menu->add([
            'title' => __('Agent Reports'),
            'icon' => '',
            'name' => 'agent-reports',
            'parent' => 'reports',
            'order' => 10,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'reports.agent',
            'module' => $module,
            'permission' => 'reports manage'
        ]);

        $menu->add([
            'title' => __('User Reports'),
            'icon' => '',
            'name' => 'user-reports',
            'parent' => 'reports',
            'order' => 15,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'reports.user',
            'module' => $module,
            'permission' => 'reports manage'
        ]);

        if (moduleIsActive('Tags')) {
            $menu->add([
                'title' => __('Tag Reports'),
                'icon' => '',
                'name' => 'tag-reports',
                'parent' => 'reports',
                'order' => 20,
                'ignore_if' => [],
                'depend_on' => [],
                'route' => 'reports.tag',
                'module' => $module,
                'permission' => 'reports manage'
            ]);
        }

        if (moduleIsActive('Ratings')) {
            $menu->add([
                'title' => __('Rating Reports'),
                'icon' => '',
                'name' => 'rating-reports',
                'parent' => 'reports',
                'order' => 25,
                'ignore_if' => [],
                'depend_on' => [],
                'route' => 'reports.rating',
                'module' => $module,
                'permission' => 'reports manage'
            ]);
        }
    }
}

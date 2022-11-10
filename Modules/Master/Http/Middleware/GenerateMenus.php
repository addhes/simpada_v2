<?php

namespace Modules\Master\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class GenerateMenus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        \Menu::make('admin_sidebar', function ($menu) {

            // Access Control Dropdown Master
            $accessControl = $menu->add('<i data-feather="columns"></i> Master<span class="menu-arrow">', [
                'class' => 'c-sidebar-nav-dropdown',
            ])
            ->data([
                'order'         => 90,
                'activematches' => [
                    'admin/category*',
                    'admin/bank*',
                    'admin/channel*',
                ],
                'permission'    => ['view_categories', 'view_banks', 'view_channels'],
            ]);
            $accessControl->link->attr([
                'class' => 'c-sidebar-nav-dropdown-toggle',
                'href'  => '#',
            ]);
            
            // Submenu: Category
            $accessControl->add('<i data-feather="layers"></i> Category', [
                'route' => 'backend.categories.index',
                'class' => 'nav-item',
            ])
            ->data([
                'order'         => 101,
                'activematches' => 'admin/categories*',
                'permission'    => ['view_categories'],
            ])
            ->link->attr([
                'class' => 'c-sidebar-nav-link',
            ]);

            // Submenu: Bank
            $accessControl->add('<i data-feather="credit-card"></i> Bank', [
                'route' => 'backend.banks.index',
                'class' => 'nav-item',
            ])
            ->data([
                'order'         => 102,
                'activematches' => 'admin/banks*',
                'permission'    => ['view_banks'],
            ])
            ->link->attr([
                'class' => 'c-sidebar-nav-link',
            ]);

            // Submenu: Channel
            $accessControl->add('<i data-feather="youtube"></i> Channel', [
                'route' => 'backend.channels.index',
                'class' => 'nav-item',
            ])
            ->data([
                'order'         => 102,
                'activematches' => 'admin/channels*',
                'permission'    => ['view_channels'],
            ])
            ->link->attr([
                'class' => 'c-sidebar-nav-link',
            ]);
        })->sortBy('order');

        \Menu::make('finance_sidebar', function ($menu) {

            // Access Control Dropdown Master
            $accessControl = $menu->add('<i data-feather="columns"></i> Master<span class="menu-arrow">', [
                'class' => 'c-sidebar-nav-dropdown',
            ])
            ->data([
                'order'         => 90,
                'activematches' => [
                    'admin/category*',
                    'admin/bank*',
                    'admin/channel*',
                ],
                'permission'    => ['view_category', 'view_bank', 'view_channel'],
            ]);
            $accessControl->link->attr([
                'class' => 'c-sidebar-nav-dropdown-toggle',
                'href'  => '#',
            ]);
            
            // Submenu: Category
            $accessControl->add('<i data-feather="layers"></i> Category', [
                'route' => 'backend.categories.index',
                'class' => 'nav-item',
            ])
            ->data([
                'order'         => 101,
                'activematches' => 'admin/categories*',
                'permission'    => ['view_categories'],
            ])
            ->link->attr([
                'class' => 'c-sidebar-nav-link',
            ]);

            // Submenu: Bank
            $accessControl->add('<i data-feather="credit-card"></i> Bank', [
                'route' => 'backend.banks.index',
                'class' => 'nav-item',
            ])
            ->data([
                'order'         => 102,
                'activematches' => 'admin/banks*',
                'permission'    => ['view_banks'],
            ])
            ->link->attr([
                'class' => 'c-sidebar-nav-link',
            ]);

            // Submenu: Channel
            $accessControl->add('<i data-feather="youtube"></i> Channel', [
                'route' => 'backend.channels.index',
                'class' => 'nav-item',
            ])
            ->data([
                'order'         => 102,
                'activematches' => 'admin/channels*',
                'permission'    => ['view_channels'],
            ])
            ->link->attr([
                'class' => 'c-sidebar-nav-link',
            ]);
        })->sortBy('order');
        
        return $next($request);
    }
}
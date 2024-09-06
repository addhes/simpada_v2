<?php

namespace Modules\Report\Http\Middleware;

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

            // Laporan
            $menu->add('<i class="fa fa-book c-sidebar-nav-icon"></i> <span>Laporan</span> ', [
                'route' => 'backend.reports.index',
                'class' => 'c-sidebar-nav-item',
            ])
            ->data([
                'order'         => 101,
                'activematches' => ['admin/reports*'],
                'permission'    => ['view_reports'],
            ])
            ->link->attr([
                'class' => 'c-sidebar-nav-link',
            ]);
        })->sortBy('order');

        // director sidebar
        \Menu::make('director_sidebar', function ($menu) {

            // Laporan
            $menu->add('<i class="fa fa-book c-sidebar-nav-icon"></i> <span>Laporan</span>', [
                'route' => 'backend.reports.index',
                'class' => 'c-sidebar-nav-item',
            ])
            ->data([
                'order'         => 101,
                'activematches' => ['admin/reports*'],
                'permission'    => ['view_reports'],
            ])
            ->link->attr([
                'class' => 'c-sidebar-nav-link',
            ]);
        })->sortBy('order');

        // finance sidebar
        \Menu::make('finance_sidebar', function ($menu) {

            // Laporan
            $menu->add('<i class="fa fa-book c-sidebar-nav-icon"></i> <span>Laporan</span>', [
                'route' => 'backend.reports.index',
                'class' => 'c-sidebar-nav-item',
            ])
            ->data([
                'order'         => 101,
                'activematches' => ['admin/reports*'],
                'permission'    => ['view_reports'],
            ])
            ->link->attr([
                'class' => 'c-sidebar-nav-link',
            ]);
        })->sortBy('order');

        return $next($request);
    }
}
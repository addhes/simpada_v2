<?php

namespace Modules\Income\Http\Middleware;

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

            // Bank Account
            $menu->add('<i class="fas fa-money-check-alt c-sidebar-nav-icon"></i> Pendapatan', [
                'route' => 'backend.incomes.index',
                'class' => 'c-sidebar-nav-item',
            ])
            ->data([
                'order'         => 100,
                'activematches' => ['admin/incomes*'],
                'permission'    => ['view_incomes'],
            ])
            ->link->attr([
                'class' => 'c-sidebar-nav-link',
            ]);
        })->sortBy('order');

        \Menu::make('finance_sidebar', function ($menu) {

            // incomes
            $menu->add('<i class="fas fa-money-check-alt c-sidebar-nav-icon"></i> Pendapatan', [
                'route' => 'backend.incomes.index',
                'class' => 'c-sidebar-nav-item',
            ])
            ->data([
                'order'         => 100,
                'activematches' => ['admin/incomes*'],
                'permission'    => ['view_incomes'],
            ])
            ->link->attr([
                'class' => 'c-sidebar-nav-link',
            ]);
        })->sortBy('order');

        return $next($request);
    }
}
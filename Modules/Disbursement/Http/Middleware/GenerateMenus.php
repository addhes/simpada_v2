<?php

namespace Modules\Disbursement\Http\Middleware;

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
            $menu->add('<i class="fa fa-credit-card c-sidebar-nav-icon"></i> Pengeluaran', [
                'route' => 'backend.disbursements.index',
                'class' => 'c-sidebar-nav-item',
            ])
            ->data([
                'order'         => 101,
                'activematches' => ['admin/disbursements*'],
                'permission'    => ['view_disbursements'],
            ])
            ->link->attr([
                'class' => 'c-sidebar-nav-link',
            ]);
        })->sortBy('order');

        \Menu::make('finance_sidebar', function ($menu) {

            // Disbursemet
            $menu->add('<i class="fa fa-credit-card c-sidebar-nav-icon"></i> Pengeluaran', [
                'route' => 'backend.disbursements.index',
                'class' => 'c-sidebar-nav-item',
            ])
            ->data([
                'order'         => 101,
                'activematches' => ['admin/disbursements*'],
                'permission'    => ['view_disbursements'],
            ])
            ->link->attr([
                'class' => 'c-sidebar-nav-link',
            ]);
        })->sortBy('order');

        return $next($request);
    }
}
<?php

namespace Modules\Submission\Http\Middleware;

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

        // Employee Sidebar
        \Menu::make('employee_sidebar', function ($menu) {
            // Access Control Dropdown Submission
            $accessControl = $menu->add('<i data-feather="file"></i> Submission<span class="menu-arrow">', [
                'class' => 'c-sidebar-nav-dropdown',
            ])
            ->data([
                'order'         => 80,
                'activematches' => [
                    'admin/submissions*',
                    'admin/accountabilities*'
                ],
                'permission'    => ['view_submissions', 'view_accountabilities'],
            ]);
            $accessControl->link->attr([
                'class' => 'c-sidebar-nav-dropdown-toggle',
                'href'  => '#',
            ]);

            // Submenu: Submission
            $accessControl->add('<i data-feather="file-plus"></i> Pengajuan', [
                'route' => 'backend.submissions.index',
                'class' => 'nav-item',
            ])
            ->data([
                'order'         => 101,
                'activematches' => 'admin/submissions*',
                'permission'    => ['view_submissions'],
            ])
            ->link->attr([
                'class' => 'c-sidebar-nav-link',
            ]);

            // Submenu: Accountability
            $accessControl->add('<i data-feather="file-text"></i> Pertanggung Jawaban', [
                'route' => 'backend.accountabilities.index',
                'class' => 'nav-item',
            ])
            ->data([
                'order'         => 102,
                'activematches' => 'admin/banks*',
                'permission'    => ['view_accountabilities'],
            ])
            ->link->attr([
                'class' => 'c-sidebar-nav-link',
            ]);
        })->sortBy('order');
        
        // Finance Sidebar
        \Menu::make('finance_sidebar', function ($menu) {
            // Access Control Dropdown Submission
            $accessControl = $menu->add('<i data-feather="file"></i> Submission<span class="menu-arrow">', [
                'class' => 'c-sidebar-nav-dropdown',
            ])
            ->data([
                'order'         => 80,
                'activematches' => [
                    'admin/submissions*',
                    'admin/accountabilities*'
                ],
                'permission'    => ['view_submissions', 'view_accountabilities'],
            ]);
            $accessControl->link->attr([
                'class' => 'c-sidebar-nav-dropdown-toggle',
                'href'  => '#',
            ]);
            
            // Submenu: Submission
            $accessControl->add('<i data-feather="file-plus"></i> Pengajuan', [
                'route' => 'backend.submissions.index',
                'class' => 'nav-item',
            ])
            ->data([
                'order'         => 101,
                'activematches' => 'admin/submissions*',
                'permission'    => ['view_submissions'],
            ])
            ->link->attr([
                'class' => 'c-sidebar-nav-link',
            ]);

            // Submenu: Accountability
            $accessControl->add('<i data-feather="file-text"></i> Pertanggung Jawaban', [
                'route' => 'backend.accountabilities.index',
                'class' => 'nav-item',
            ])
            ->data([
                'order'         => 102,
                'activematches' => 'admin/banks*',
                'permission'    => ['view_accountabilities'],
            ])
            ->link->attr([
                'class' => 'c-sidebar-nav-link',
            ]);

            // Submenu: Approval Finance
            $accessControl->add('<i data-feather="check"></i> Approval Finance', [
                'route' => 'backend.approvalfinances.index',
                'class' => 'nav-item',
            ])
            ->data([
                'order'         => 103,
                'activematches' => 'admin/approvalfinances*',
                'permission'    => ['view_approvalfinances'],
            ])
            ->link->attr([
                'class' => 'c-sidebar-nav-link',
            ]);
        })->sortBy('order');

        // Director Sidebar
        \Menu::make('director_sidebar', function ($menu) {
            // Access Control Dropdown Submission
            $accessControl = $menu->add('<i data-feather="file"></i> Submission<span class="menu-arrow">', [
                'class' => 'c-sidebar-nav-dropdown',
            ])
            ->data([
                'order'         => 80,
                'activematches' => [
                    'admin/submissions*',
                    'admin/accountabilities*'
                ],
                'permission'    => ['view_submissions', 'view_accountabilities'],
            ]);
            $accessControl->link->attr([
                'class' => 'c-sidebar-nav-dropdown-toggle',
                'href'  => '#',
            ]);
            
            // Submenu: Submission
            $accessControl->add('<i data-feather="file-plus"></i> Pengajuan', [
                'route' => 'backend.submissions.index',
                'class' => 'nav-item',
            ])
            ->data([
                'order'         => 101,
                'activematches' => 'admin/submissions*',
                'permission'    => ['view_submissions'],
            ])
            ->link->attr([
                'class' => 'c-sidebar-nav-link',
            ]);

            // Submenu: Accountability
            $accessControl->add('<i data-feather="file-text"></i> Pertanggung Jawaban', [
                'route' => 'backend.accountabilities.index',
                'class' => 'nav-item',
            ])
            ->data([
                'order'         => 102,
                'activematches' => 'admin/banks*',
                'permission'    => ['view_accountabilities'],
            ])
            ->link->attr([
                'class' => 'c-sidebar-nav-link',
            ]);

            // Submenu: Approval Director
            $accessControl->add('<i data-feather="check"></i> Approval Director', [
                'route' => 'backend.approvaldirectors.index',
                'class' => 'nav-item',
            ])
            ->data([
                'order'         => 104,
                'activematches' => 'admin/approvaldirectors*',
                'permission'    => ['view_approvaldirectors'],
            ])
            ->link->attr([
                'class' => 'c-sidebar-nav-link',
            ]);

            // Submenu: Approval Submission Urgent
            $accessControl->add('<i data-feather="check"></i> Approval Urgent', [
                'route' => 'backend.approvalurgents.index',
                'class' => 'nav-item',
            ])
            ->data([
                'order'         => 104,
                'activematches' => 'admin/approvalurgents*',
                'permission'    => ['view_approvalurgents'],
            ])
            ->link->attr([
                'class' => 'c-sidebar-nav-link',
            ]);

            // Submenu: Accountability Report
            // $accessControl->add('<i data-feather="check"></i> Accountability Report', [
            //     'route' => 'backend.accountabilityreports.index',
            //     'class' => 'nav-item',
            // ])
            // ->data([
            //     'order'         => 104,
            //     'activematches' => 'admin/accountabilityreports*',
            //     'permission'    => ['accountabilityreports'],
            // ])
            // ->link->attr([
            //     'class' => 'c-sidebar-nav-link',
            // ]);
        })->sortBy('order');

        // admin sidebar
        \Menu::make('admin_sidebar', function ($menu) {
            // Access Control Dropdown Submission
            $accessControl = $menu->add('<i data-feather="file"></i> Submission<span class="menu-arrow">', [
                'class' => 'c-sidebar-nav-dropdown',
            ])
            ->data([
                'order'         => 80,
                'activematches' => [
                    'admin/submissions*',
                    'admin/accountabilities*'
                ],
                'permission'    => ['view_submissions', 'view_accountabilities'],
            ]);
            $accessControl->link->attr([
                'class' => 'c-sidebar-nav-dropdown-toggle',
                'href'  => '#',
            ]);
            
            // Submenu: Submission
            $accessControl->add('<i data-feather="file-plus"></i> Pengajuan', [
                'route' => 'backend.submissions.index',
                'class' => 'nav-item',
            ])
            ->data([
                'order'         => 101,
                'activematches' => 'admin/submissions*',
                'permission'    => ['view_submissions'],
            ])
            ->link->attr([
                'class' => 'c-sidebar-nav-link',
            ]);

            // Submenu: Accountability
            $accessControl->add('<i data-feather="file-text"></i> Pertanggung Jawaban', [
                'route' => 'backend.accountabilities.index',
                'class' => 'nav-item',
            ])
            ->data([
                'order'         => 102,
                'activematches' => 'admin/banks*',
                'permission'    => ['view_accountabilities'],
            ])
            ->link->attr([
                'class' => 'c-sidebar-nav-link',
            ]);

            // Submenu: Approval Finance
            $accessControl->add('<i data-feather="check"></i> Approval Finance', [
                'route' => 'backend.approvalfinances.index',
                'class' => 'nav-item',
            ])
            ->data([
                'order'         => 103,
                'activematches' => 'admin/approvalfinances*',
                'permission'    => ['view_approvalfinances'],
            ])
            ->link->attr([
                'class' => 'c-sidebar-nav-link',
            ]);

            // Submenu: Approval Director
            $accessControl->add('<i data-feather="check"></i> Approval Director', [
                'route' => 'backend.approvaldirectors.index',
                'class' => 'nav-item',
            ])
            ->data([
                'order'         => 104,
                'activematches' => 'admin/approvaldirectors*',
                'permission'    => ['view_approvaldirectors'],
            ])
            ->link->attr([
                'class' => 'c-sidebar-nav-link',
            ]);

        })->sortBy('order');

        return $next($request);
    }
}
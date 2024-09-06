<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Str;

class GenerateMenus
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Director Sidebar
        \Menu::make('director_sidebar', function ($menu) {
            // Dashboard
            $menu->add('<i data-feather="home"></i><span>Dashboard</span> ', [
                'route' => 'director.dashboard',
                'class' => 'c-sidebar-nav-item',
            ])
            ->data([
                'order'         => 1,
                'activematches' => 'director/dashboard*',
            ])
            ->link->attr([
                'class' => 'c-sidebar-nav-link',
            ]);
        })->sortBy('order');

        // Employee Sidebar
        \Menu::make('employee_sidebar', function ($menu) {
            // Dashboard
            $menu->add('<i data-feather="home"></i> <span>Dashboard</span>', [
                'route' => 'employee.dashboard',
                'class' => 'c-sidebar-nav-item',
            ])
            ->data([
                'order'         => 1,
                'activematches' => 'employee/dashboard*',
            ])
            ->link->attr([
                'class' => 'c-sidebar-nav-link',
            ]);
        })->sortBy('order');

        // Finance Sidebar
        \Menu::make('finance_sidebar', function ($menu) {
            // Dashboard
            $menu->add('<i data-feather="home"></i> <span>Dashboard</span>', [
                'route' => 'finance.dashboard',
                'class' => 'c-sidebar-nav-item',
            ])
            ->data([
                'order'         => 1,
                'activematches' => 'finance/dashboard*',
            ])
            ->link->attr([
                'class' => 'c-sidebar-nav-link',
            ]);
        })->sortBy('order');
        
        // Admin Sidebar
        \Menu::make('admin_sidebar', function ($menu) {
            // Dashboard
            $menu->add('<i data-feather="home"></i> <span>Dashboard</span>', [
                'route' => 'backend.dashboard',
            ])
            ->data([
                'order'         => 1,
                'activematches' => 'admin/dashboard*',
            ]);

            // Separator: Access Management
            $menu->add('Management', [
                'class' => 'menu-title',
            ])
            ->data([
                'order'         => 101,
                'permission'    => ['edit_settings', 'view_backups', 'view_users', 'view_roles', 'view_logs'],
            ]);

            // Parameters
            $menu->add('<i data-feather="parameters"></i> <span>Parameters</span>', [
                'route' => 'backend.parameters',
            ])
            ->data([
                'order'         => 102,
                'activematches' => 'admin/parameters*',
                'permission'    => ['view_parameters'],
            ]);

            // Notification Phone Number
            $menu->add('<i data-feather="parameters"></i> <span>Notification Phone</span>', [
                'route' => 'backend.notification_phones',
            ])
            ->data([
                'order'         => 102,
                'activematches' => 'admin/notification_phones*',
                'permission'    => ['view_notification_phones'],
            ]);

            // Settings
            $menu->add('<i data-feather="settings"></i> <span>Settings</span>', [
                'route' => 'backend.settings',
            ])
            ->data([
                'order'         => 102,
                'activematches' => 'admin/settings*',
                'permission'    => ['edit_settings'],
            ]);

            // Backup
            $menu->add('<i data-feather="archive"></i> <span>Backups</span>', [
                'route' => 'backend.backups.index',
                'class' => 'nav-item',
            ])
            ->data([
                'order'         => 103,
                'activematches' => 'admin/backups*',
                'permission'    => ['view_backups'],
            ]);

            // Access Control Dropdown
            $accessControl = $menu->add('<i data-feather="shield"></i> <span>Access Control</span><span class="menu-arrow"></span>', [
                'class' => '',
            ])
            ->data([
                'order'         => 104,
                'activematches' => [
                    'admin/users*',
                    'admin/roles*',
                ],
                'permission'    => ['view_users', 'view_roles'],
            ]);
            $accessControl->link->attr([
                'class' => 'c-sidebar-nav-dropdown-toggle',
                'href'  => '#',
            ]);

            // Submenu: Users
            $accessControl->add('Users', [
                'route' => 'backend.users.index',
                'class' => 'nav-item',
            ])
            ->data([
                'order'         => 105,
                'activematches' => 'admin/users*',
                'permission'    => ['view_users'],
            ])
            ->link->attr([
                'class' => 'c-sidebar-nav-link',
            ]);

            // Submenu: Roles
            $accessControl->add('Roles', [
                'route' => 'backend.roles.index',
                'class' => 'nav-item',
            ])
            ->data([
                'order'         => 106,
                'activematches' => 'admin/roles*',
                'permission'    => ['view_roles'],
            ])
            ->link->attr([
                'class' => 'c-sidebar-nav-link',
            ]);

            // Log Viewer
            // Log Viewer Dropdown
            $accessControl = $menu->add('<i data-feather="list"></i> <span>Log Viewer</span> <span class="menu-arrow"></span>', [
                'class' => 'c-sidebar-nav-dropdown',
            ])
            ->data([
                'order'         => 107,
                'activematches' => [
                    'log-viewer*',
                ],
                'permission'    => ['view_logs'],
            ]);
            $accessControl->link->attr([
                'class' => 'c-sidebar-nav-dropdown-toggle',
                'href'  => '#',
            ]);

            // Submenu: Log Viewer Dashboard
            $accessControl->add('Dashboard', [
                'route' => 'log-viewer::dashboard',
                'class' => 'nav-item',
            ])
            ->data([
                'order'         => 108,
                'activematches' => 'admin/log-viewer',
            ])
            ->link->attr([
                'class' => 'c-sidebar-nav-link',
            ]);

            // Submenu: Log Viewer Logs by Days
            $accessControl->add('Logs by Days', [
                'route' => 'log-viewer::logs.list',
                'class' => 'nav-item',
            ])
            ->data([
                'order'         => 109,
                'activematches' => 'admin/log-viewer/logs*',
            ])
            ->link->attr([
                'class' => 'c-sidebar-nav-link',
            ]);

            // Access Permission Check
            $menu->filter(function ($item) {
                if ($item->data('permission')) {
                    if (auth()->check()) {
                        if (auth()->user()->hasRole('super admin')) {
                            return true;
                        } elseif (auth()->user()->hasAnyPermission($item->data('permission'))) {
                            return true;
                        }
                    }
                    return false;
                } else {
                    return true;
                }
            });

            // Set Active Menu
            $menu->filter(function ($item) {
                if ($item->activematches) {
                    $matches = is_array($item->activematches) ? $item->activematches : [$item->activematches];

                    foreach ($matches as $pattern) {
                        if (Str::is($pattern, \Request::path())) {
                            $item->activate();
                            $item->active();
                            if ($item->hasParent()) {
                                $item->parent()->activate();
                                $item->parent()->active();
                            }
                            // dd($pattern);
                        }
                    }
                }
                return true;
            });
        })->sortBy('order');

        return $next($request);
    }
}
<?php

namespace App\Http\Controllers;


class MenuController extends Controller
{
    public function load()
    {
        return [
            [
                'id' => 'dashboard',
                'icon' => "mdi mdi-view-dashboard-outline",
                'label' => "Dashboard",
                'to' => "/dashboard"
            ],
            [
                'id' => 'companies',
                'icon' => "mdi mdi-office-building",
                'label' => "Companies",
                'to' => "/companies"
            ],
            [
                'id' => 'employees',
                'icon' => "mdi mdi-account-tie-outline",
                'label' => "Employees",
                'to' => "/employees"
            ],
            $this->settings(),
        ];
    }

    private function settings()
    {
        $items = [
            [
                'id' => rand(),
                'icon' => "mdi mdi-account-circle-outline",
                'to' => "/profile-settings/profile",
                'label' => "My Profile",
            ],
            [
                'id' => rand(),
                'icon' => "mdi mdi-account-supervisor-outline",
                'label' => "Users",
                'to' => "/users",
            ],
        ];
        return [
            'id' => "settings",
            'icon' => "mdi mdi-settings-outline",
            'label' => "Settings",
            'subs' => $items,
        ];
    }
}

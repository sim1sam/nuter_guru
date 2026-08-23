<?php

namespace App\Helpers;

use Illuminate\Http\Request;

class AccountNavHelper
{
    public static function routes(): array
    {
        return [
            'dashboard' => [
                'url' => 'dashboard',
                'routes' => ['dashboard', 'user.dashboard'],
                'icon' => 'fa-tachometer-alt',
                'label' => 'Dashboard',
            ],
            'profile' => [
                'url' => 'profile',
                'routes' => ['profile', 'user.profile'],
                'icon' => 'fa-user',
                'label' => 'Profile',
            ],
            'orders' => [
                'url' => 'orders',
                'routes' => ['orders', 'orders.show', 'user.orders', 'user.orders.show'],
                'icon' => 'fa-shopping-bag',
                'label' => 'Orders',
            ],
            'wishlist' => [
                'url' => 'wishlist',
                'routes' => ['wishlist', 'user.wishlist'],
                'icon' => 'fa-heart',
                'label' => 'Wishlist',
            ],
            'addresses' => [
                'url' => 'addresses.index',
                'routes' => ['addresses.index', 'addresses.create', 'addresses.edit', 'addresses.show'],
                'icon' => 'fa-map-marker-alt',
                'label' => 'Addresses',
            ],
        ];
    }

    public static function isActive(array $routeNames, ?Request $request = null): bool
    {
        $request = $request ?: request();

        foreach ($routeNames as $name) {
            if ($request->routeIs($name)) {
                return true;
            }
        }

        return false;
    }

    public static function isAccountPage(?Request $request = null): bool
    {
        $request = $request ?: request();
        $allRoutes = [];

        foreach (self::routes() as $item) {
            $allRoutes = array_merge($allRoutes, $item['routes']);
        }

        return self::isActive($allRoutes, $request);
    }

    public static function isDashboard(?Request $request = null): bool
    {
        return self::isActive(['dashboard', 'user.dashboard'], $request);
    }
}

<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Employee;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Target;
use App\Models\User;
use App\Policies\CategoryPolicy;
use App\Policies\DashboardPolicy;
use App\Policies\EmployeePolicy;
use App\Policies\ProductPolicy;
use App\Policies\PurchasePolicy;
use App\Policies\ReportPolicy;
use App\Policies\RolePolicy;
use App\Policies\SalePolicy;
use App\Policies\TargetPolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Category::class => CategoryPolicy::class,
        Employee::class => EmployeePolicy::class,
        Product::class => ProductPolicy::class,
        Purchase::class => PurchasePolicy::class,
        Sale::class => SalePolicy::class,
        Target::class => TargetPolicy::class,
        User::class => UserPolicy::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register policies
        foreach ($this->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }

        // Define additional gates for menu access
        Gate::define('dashboard-menu', function ($user) {
            return $user->can('dashboard: menu');
        });

        Gate::define('reports-menu', function ($user) {
            return $user->can('reports: menu');
        });

        Gate::define('purchases-menu', function ($user) {
            return $user->can('purchases: menu');
        });

        Gate::define('users-menu', function ($user) {
            return $user->can('users: menu');
        });

        Gate::define('roles-menu', function ($user) {
            return $user->can('roles: menu');
        });

        Gate::define('permissions-menu', function ($user) {
            return $user->can('permissions: menu');
        });
    }
}

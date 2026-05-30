<?php
namespace App\Providers;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Incident;
use App\Policies\IncidentPolicy;

class AppServiceProvider extends ServiceProvider {
    public function register(): void {}
    public function boot(): void {
        Gate::policy(Incident::class, IncidentPolicy::class);
    }
}

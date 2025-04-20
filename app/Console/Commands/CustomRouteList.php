<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;

class CustomRouteList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:custom-route-list {--except-vendor}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all application routes (with the ability to exclude vendor routes)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Check if the --except-vendor flag is set
        $excludeVendor = $this->option('except-vendor');

        // Get the application routes
        $routes = Route::getRoutes();

        // Filter out vendor routes if the flag is set
        if ($excludeVendor) {
            $routes = collect($routes)->filter(function ($route) {
                // Filter out routes that are from the vendor directory
                return strpos($route->getActionName(), 'Illuminate\\') === false;
            });
        }

        // Format the output for the route list
        $this->table(
            ['Method', 'URI', 'Name', 'Action', 'Middleware'],
            $routes->map(function ($route) {
                return [
                    $route->methods()[0],
                    $route->uri(),
                    $route->getName() ?? '-',
                    $route->getActionName(),
                    implode(', ', $route->gatherMiddleware())
                ];
            })
        );
    }
}

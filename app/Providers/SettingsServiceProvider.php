 <?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        try {
            config([
                'broadcasting.connections.pusher.key' => globalSettings()->pusher_app_key,
                'broadcasting.connections.pusher.secret' => globalSettings()->pusher_app_secret,
                'broadcasting.connections.pusher.app_id' => globalSettings()->pusher_app_id,
                'broadcasting.connections.pusher.options.cluster' => globalSettings()->pusher_app_cluster,
            ]);
        } catch (\Exception $e) {
            //
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}

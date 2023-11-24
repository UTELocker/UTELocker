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

            if (!user()->isSupperUser()) {
                config([
                    'mail.default' => siteGroup()->email_mailer,
                    'mail.mailers.smtp.host' => siteGroup()->email_host,
                    'mail.mailers.smtp.port' => siteGroup()->email_port,
                    'mail.mailers.smtp.encryption' => siteGroup()->email_encryption,
                    'mail.mailers.smtp.username' => siteGroup()->email_username,
                    'mail.mailers.smtp.password' => siteGroup()->email_password,
                    'mail.from.address' => siteGroup()->email_from_address,
                ]);
            }
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

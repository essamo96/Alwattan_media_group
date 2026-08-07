<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Broadcast;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // The app's default auth guard is "teacher" (config/auth.php), and the
        // admin panel authenticates on the "admin" guard (auth:admin). Pusher's
        // private-channel auth POSTs to /broadcasting/auth, so that route must
        // run under the "admin" guard (session + auth:admin) for
        // auth('admin')->check() in routes/channels.php to succeed.
        Broadcast::routes(['middleware' => ['web', 'auth:admin']]);

        require base_path('routes/channels.php');
    }
}

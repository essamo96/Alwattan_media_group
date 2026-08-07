<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Admin course-registration live notifications. Auth uses the "admin" guard
// (this app's default guard is "teacher" - see config/auth.php), so the
// channel callback checks auth('admin') explicitly rather than relying on
// the guard Broadcast::routes() resolves by default.
Broadcast::channel('admin-notifications', function ($user) {
    return auth('admin')->check();
});

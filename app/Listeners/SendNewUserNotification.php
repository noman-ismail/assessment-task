<?php

namespace App\Listeners;

use App\Events\NewUserRegistered;
use App\Models\User;
use App\Notifications\NewUserNotification;
use Illuminate\Support\Facades\Notification;

class SendNewUserNotification
{
    public function __construct()
    {
        //
    }

    public function handle(NewUserRegistered $event)
    {
        // Find the super admin(s)
        $superAdmins = User::role('super-admin')->get();
        foreach ($superAdmins as $superAdmin) {
            Notification::send($superAdmin, new NewUserNotification($event->user, $superAdmin->email));
        }
    }
}

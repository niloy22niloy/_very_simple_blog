<?php

namespace App\Listeners;

use App\Events\PostEvent;
use App\Mail\UserMail;
use App\Models\User;
use App\Models\UserLogin;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class NotifyUser implements ShouldQueue
{
    use InteractsWithQueue;
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PostEvent $event): void
    {
        //
        Log::info('NotifyUser listener triggered.', ['post' => $event->post]);
        $users = UserLogin::all()->take(5);
        foreach($users as $user){
            Mail::to($user->email)->queue(new UserMail($event->post));
        }
    }
}

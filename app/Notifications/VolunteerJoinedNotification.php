<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class VolunteerJoinedNotification extends Notification
{
    use Queueable;

    public function __construct(private readonly User $volunteer)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'kind' => 'volunteer_joined',
            'title' => 'New volunteer joined',
            'message' => sprintf(
                '%s just registered from %s.',
                $this->volunteer->name,
                $this->volunteer->city ?: 'an unknown city'
            ),
            'url' => route('admin.volunteers.index', ['search' => $this->volunteer->email]),
        ];
    }
}

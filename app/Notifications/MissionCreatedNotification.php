<?php

namespace App\Notifications;

use App\Models\Mission;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class MissionCreatedNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly Mission $mission,
        private readonly string $createdBy
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'kind' => 'mission_created',
            'title' => 'New mission created',
            'message' => sprintf(
                '%s created "%s" for %s.',
                $this->createdBy,
                $this->mission->title,
                $this->mission->display_location
            ),
            'url' => route('admin.missions.show', $this->mission),
        ];
    }
}

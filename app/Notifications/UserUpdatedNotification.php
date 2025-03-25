<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;

class UserUpdatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     *
     * @var User
     */
    protected $user;

    /**
     * @var array<string, array<string, string>>
     */

    protected $changedAttributes;

    public function __construct(User $user, array $changedAttributes)
    {
        $this->user = $user;
        $this->changedAttributes = $changedAttributes;
    }

    /**
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {

        $message = (new MailMessage)
            ->subject('User Profile Updated: ' . $this->user->name)
            ->greeting('Hello!')
            ->line('User **' . $this->user->name . '** (ID: ' . $this->user->id . ') has updated their profile information.')
            ->line('**Changed Fields:**');

            foreach ($this->changedAttributes as $field => $values) {
                $message = $message->line('**' . ucfirst($field) . '**:')
                        ->line('- Previous: ' . ($values['from'] ?? '(empty)'))
                        ->line('- New: ' . ($values['to'] ?? '(empty)'));
            }

            return $message->line('Thank you for using our application');
    }

}

<?php

namespace App\Notifications;

use App\Model\Reply;
use Illuminate\Bus\Queueable;
use App\Http\Resources\ReplyResource;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class NewReplyNotification extends Notification
{
    use Queueable;

    public $reply;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Reply $reply)
    {
        $this->reply = $reply;//ここである特定のリプライとのつながり
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            "replyBy" => $this->reply->user->name,
            "question" => $this->reply->question->title,
            "path" => $this->reply->question->path
        ];
    }

    public function toBroadcast($notifiable)
{
    return new BroadcastMessage([
            "replyBy" => $this->reply->user->name,
            "question" => $this->reply->question->title,
            "path" => $this->reply->question->path,
            "reply" => new ReplyResource($this->reply),
            "class" => "replied"
    ]);
}
}

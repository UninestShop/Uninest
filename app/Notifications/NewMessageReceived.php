<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Chat;

class NewMessageReceived extends Notification implements ShouldQueue
{
    use Queueable;

    protected $message;
    protected $isSeller;

    /**
     * Create a new notification instance.
     *
     * @param Chat $message
     * @param bool $isSeller Whether the recipient is a seller
     * @return void
     */
    public function __construct(Chat $message, bool $isSeller = false)
    {
        $this->message = $message;
        $this->isSeller = $isSeller;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = route('messages.show', $this->message);
        $sender = $this->message->sender;
        $product = $this->message->product;
        
        $title = $this->isSeller
            ? "New message about your product: {$product->name}"
            : "New message from seller";

        return (new MailMessage)
            ->subject($title)
            ->greeting("Hello {$notifiable->name}!")
            ->line("{$sender->name} has sent you a message.")
            ->line("Message: \"{$this->message->message}\"")
            ->action('View Conversation', $url)
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
            'message_id' => $this->message->id,
            'chat_id' => $this->message->id,
            'sender_id' => $this->message->sender_id,
            'sender_name' => $this->message->sender->name,
            'product_id' => $this->message->product_id,
            'product_name' => $this->message->product->name ?? 'Unknown Product',
            'message_preview' => \Illuminate\Support\Str::limit($this->message->message, 50),
            'is_seller' => $this->isSeller,
        ];
    }
}

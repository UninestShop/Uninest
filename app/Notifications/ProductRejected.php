<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Product;

class ProductRejected extends Notification implements ShouldQueue
{
    use Queueable;
    
    protected $product;
    
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
    }
    
    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }
    
    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = route('seller.products.edit', $this->product);
        
        return (new MailMessage)
            ->subject('Product Rejection')
            ->greeting('Dear ' . $notifiable->name . ',')
            ->line('Thank you for submitting your product, "' . $this->product->name . '". We appreciate your interest and the effort you\'ve put into your submission.')
            ->line('After careful consideration, we regret to inform you that your product does not meet our current listing criteria and has not been approved for publication on our platform.')
            ->line('This decision may be due to one or more of the following reasons:')
            ->line('• Incomplete or inaccurate product information')
            ->line('• Misalignment with our platform guidelines or quality standards')
            ->line('• Prohibited or restricted content')
            // ->line('Specific reason: ' . ($this->product->rejection_reason ?? 'Not specified'))
            // ->action('Edit Your Product', $url)
            ->line('If you would like to revise and resubmit your product, please ensure it adheres to our submission guidelines and includes all necessary details and documentation.')
            ->line('We appreciate your understanding and encourage you to reach out if you have any questions or need further clarification.')
            ->line('Best regards,')
            ->salutation('UniNest Team');
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
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'message' => 'Your product has been rejected',
            'type' => 'product_rejected'
        ];
    }
}

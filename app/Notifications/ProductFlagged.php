<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Product;

class ProductFlagged extends Notification implements ShouldQueue
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
            ->subject('Your Product Has Been Flagged')
            ->line('Your product "' . $this->product->name . '" has been flagged for review.')
            ->line('This could be due to policy violations or other concerns. Please review your product details.')
            ->action('Review Your Product', $url)
            ->line('If you believe this is a mistake, please contact our support team.');
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
            'message' => 'Your product has been flagged for review',
            'type' => 'product_flagged'
        ];
    }
}

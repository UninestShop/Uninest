<?php

namespace App\Notifications;

use App\Models\ProductReport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProductReported extends Notification implements ShouldQueue
{
    use Queueable;

    protected $report;

    /**
     * Create a new notification instance.
     */
    public function __construct(ProductReport $report)
    {
        $this->report = $report;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Product Reported: ' . $this->report->product->name)
            ->line('A product has been reported by a user.')
            ->line('Product: ' . $this->report->product->name)
            ->line('Reason: ' . $this->report->reason)
            ->line('Reported by: ' . $this->report->user->name)
            ->action('View Report', url('/admin/reports/' . $this->report->id))
            ->line('Please review this report and take appropriate action.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'report_id' => $this->report->id,
            'product_id' => $this->report->product_id,
            'product_name' => $this->report->product->name,
            'reporter_name' => $this->report->user->name,
            'reason' => $this->report->reason,
        ];
    }
}

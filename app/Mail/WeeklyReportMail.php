<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Models\{Space, Spending};
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\{Content, Envelope};

class WeeklyReportMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * The reportable space.
     *
     * @var \App\Models\Space
     */
    public $space;

    /**
     * The reportable week.
     *
     * @var int
     */
    public $week;

    /**
     * The weekly total spent.
     *
     * @var int
     */
    public $totalSpent;

    /**
     * The highest spent of the week.
     *
     * @var \App\Models\Spending
     */
    public $highestSpent;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(
        Space $space,
        int $week,
        int $totalSpent,
        ?Spending $highestSpent
    ) {
        $this->space = $space;
        $this->week = $week;
        $this->totalSpent = $totalSpent;
        $this->highestSpent = $highestSpent;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Here is your weekly report!',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'emails.weekly_report',
            with: [
                'space' => $this->space,
                'week' => $this->week,
                'totalSpent' => $this->totalSpent,
                'highestSpent' => $this->highestSpent,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}

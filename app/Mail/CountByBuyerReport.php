<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CountByBuyerReport extends Mailable
{
    use Queueable, SerializesModels;


    public $buyerStats;
    public $startDate;
    public $endDate;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($buyerStats, $startDate, $endDate)
    {
        $this->buyerStats = $buyerStats;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        //default logo url
        $logoUrl = asset('/images/logo.png');

        $fromName = env('MAIL_FROM_NAME');
        $fromEmail = env('MAIL_FROM_ADDRESS');
        
        $this->subject('AIM Lead Engine : Count by Buyer Report Available.')
            ->from($fromEmail, $fromName)
            ->view('emails.reports.count-by-buyer',[
                'startDate' => $this->startDate,
                'endDate' => $this->endDate
            ]);

        $reportPdf = \PDF::loadView('pdf.count-by-buyer',[
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'logoUrl' => $logoUrl,
            'buyerStats' => $this->buyerStats
        ])->stream();

        $this->attachData($reportPdf,'Count by Buyer Report.pdf',[
            'mime' => 'application/pdf'
        ]);
    }
}

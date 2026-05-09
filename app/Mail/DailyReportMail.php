<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Mail\Mailables\Attachment;
use Carbon\Carbon;

class DailyReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $appointments;
    public $role;

    /**
     * Create a new message instance.
     */
    public function __construct($appointments, $role)
    {
        $this->appointments = $appointments;
        $this->role = $role;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Reporte Diario de Citas - Modo: {$this->role}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.reports.daily',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $pdf = Pdf::loadView('pdf.daily-report', [
            'appointments' => $this->appointments,
            'recipientType' => $this->role,
            'date' => Carbon::today()->format('d/m/Y')
        ]);
        
        return [
            Attachment::fromData(fn () => $pdf->output(), 'reporte-diario-' . Carbon::today()->format('Y-m-d') . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}

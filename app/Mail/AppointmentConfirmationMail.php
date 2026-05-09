<?php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;

class AppointmentConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $appointment;

    /**
     * Create a new message instance.
     */
    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirmación de Cita Médica - Healthify',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.appointments.confirmation',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        // Asegurar que las relaciones necesarias estén cargadas para la vista del PDF
        $this->appointment->load(['patient.user', 'doctor']);
        
        $pdf = Pdf::loadView('pdf.appointment-voucher', ['appointment' => $this->appointment]);
        
        return [
            Attachment::fromData(fn () => $pdf->output(), 'comprobante-cita.pdf')
                ->withMime('application/pdf'),
        ];
    }
}

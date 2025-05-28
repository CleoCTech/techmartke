<?php

namespace App\Mail;

use App\Models\DepartmentRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DepartmentRequestNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $departmentRequest;
    /**
     * Create a new message instance.
     */
    public function __construct(DepartmentRequest $departmentRequest)
    {
        $this->departmentRequest = $departmentRequest;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Department Request Notification',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.department-request', // Make sure you create this view
            with: [
                'requestTitle' => $this->departmentRequest->title,
                'requestDescription' => $this->departmentRequest->description,
                'fromDepartment' => $this->departmentRequest->fromDepartment->name,
                'toDepartment' => $this->departmentRequest->toDepartment->name,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}

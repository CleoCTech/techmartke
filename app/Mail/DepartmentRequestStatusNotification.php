<?php

namespace App\Mail;

use App\Models\DepartmentRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DepartmentRequestStatusNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $departmentRequest;
    public $status;
    public $remarks;
    /**
     * Create a new message instance.
     */
    public function __construct(DepartmentRequest $departmentRequest, $status, $remarks = null)
    {
        $this->departmentRequest = $departmentRequest;
        $this->status = $status;
        $this->remarks = $remarks;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Department Request Status Update',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.department-request-status', // Create this Blade template
            with: [
                'requestTitle' => $this->departmentRequest->title,
                'requestStatus' => $this->status == 2 ? 'Approved' : 'Rejected',
                'remarks' => $this->remarks,
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

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TutorAcceptedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $tutor;
    public $classInfo;
    public $parentInfo;
    // public $classDetailUrl;

    /**
     * Create a new message instance.
     */
    public function __construct($tutor, $classInfo, $parentInfo)
    {
        $this->tutor = $tutor;
        $this->classInfo = $classInfo;
        $this->parentInfo = $parentInfo;
        // $this->classDetailUrl = $classDetailUrl;
    }



    public function build()
    {
        return $this->subject("Nhận lớp học mới thành công")
            ->view('emails.tutor_accepted');
    }

    /**
     * Get the message envelope.
     */
    // public function envelope(): Envelope
    // {
    //     return new Envelope(
    //         subject: 'Tutor Accepted Mail',
    //     );
    // }

    /**
     * Get the message content definition.
     */
    // public function content(): Content
    // {
    //     return new Content(
    //         view: 'view.name',
    //     );
    // }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    // public function attachments(): array
    // {
    //     return [];
    // }
}

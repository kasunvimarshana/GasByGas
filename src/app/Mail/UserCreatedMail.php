<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;
use App\Models\User;

class UserCreatedMail extends Mailable {
    use Queueable, SerializesModels;

    public $user;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user) {
        //
        $this->user = $user;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope {
        $subject = trans('Welcome, :name!', ['name' => $this->user->name]);
        $from = new Address(config('mail.from.address'), config('mail.from.name'));
        $replyTo = [new Address(config('mail.reply_to.address'), config('mail.reply_to.name'))];
        return new Envelope(
            subject: $subject,
            from: $from,
            // replyTo: $replyTo,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content {
        return new Content(
            markdown: 'emails.user_created',
            with: [
                'user' => $this->user,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array {
        return [
            // Example: Adding a dynamic PDF attachment
            // \Illuminate\Mail\Mailables\Attachment::fromPath(storage_path('pdfs/welcome.pdf'))
            //     ->as('welcome.pdf')
            //     ->withMime('application/pdf'),
        ];
    }

    public function build() {
        $subject = trans('Welcome, :name!', ['name' => $this->user->name]);
        return $this
            ->from(config('mail.from.address'), config('mail.from.name'))
            // ->replyTo(config('mail.reply_to.address'), config('mail.reply_to.name'))
            ->subject($subject)
            ->markdown('emails.user_created', [
                'user' => $this->user,
            ]);
    }
}

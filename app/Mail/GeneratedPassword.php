<?php

namespace App\Mail;

use App\Models\Token;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class GeneratedPassword extends Mailable
{
    use Queueable, SerializesModels;

    private Token $token;
    private User $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Token $token, User $user)
    {
        $this->token = $token;
        $this->user = $user;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Acesso ao sistema',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        $url = \env('URL_FRONT');
        return new Content(
            view: null, 
            html: null, 
            text: null, 
            markdown: 'mail.generatedPassword', 
            with: [
                'token' => $this->token,
                'url' => "{$url}/user/reset-password?token={$this->token->token}",
                'user' => $this->user
            ], 
            htmlString: null
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

<?php

namespace App\Mail;

use App\Models\Token;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RecoverPassword extends Mailable {

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
        $this->user = $user;
        $this->token = $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {

        $url = env('URL_FRONT');

        return $this->subject('Recuperação de Senha')
                        ->view('mail.recoverPassword')
                        ->with([
                            'token' => $this->token,
                            'url' => $url,
                            'user' => $this->user
        ]);
    }

}

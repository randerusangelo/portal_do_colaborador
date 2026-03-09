<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendContatoDPO extends Mailable
{
    use Queueable, SerializesModels;

    private $assunto, $mensagem;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct( $assunto, $mensagem )
    {
        $this->assunto = $assunto;
        $this->mensagem = $mensagem;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('no-reply@usangelo.com.br')
                    ->to('privacidade@usangelo.com.br')
                    ->subject('Contato DPO - ' . $this->assunto)
                    ->with([
                        'matricula' => Auth()->user()->matricula,
                        'nome'      => Auth()->user()->nome . ' ' . Auth()->user()->sobrenome,
                        'email'     => Auth()->user()->email,
                        'mensagem'  => $this->mensagem
                    ])
                    ->view('mails.sendContatoDPO');
    }
}

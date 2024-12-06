<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendDocumentMail extends Mailable
{
    use Queueable, SerializesModels;

    public array $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function build(): SendDocumentMail
    {
        $subject = ($this->data['MailType'] == 'Approve') ? 'Evrak Talebi Onayı' : 'Evrak Talebi Reddi';

        $text = ($this->data['MailType'] == 'Approve')
                ? "<p>&nbsp;&nbsp;&nbsp;Sayın {$this->data['ToUserFirstName']} {$this->data['ToUserLastName']},</p>
           <p>{$this->data['CreateDate']} tarihinde {$this->data['DocumentName']} isminde yüklemiş olduğunuz evrak talebiniz insan kaynakları personelimiz {$this->data['FromUserFirstName']} {$this->data['FromUserLastName']} tarafından onaylanmıştır.</p>"
                : "<p>&nbsp;&nbsp;&nbsp;Sayın {$this->data['ToUserFirstName']} {$this->data['ToUserLastName']},</p>
           <p>{$this->data['CreateDate']} tarihinde {$this->data['DocumentName']} isminde yüklemiş olduğunuz evrak talebiniz insan kaynakları personelimiz {$this->data['FromUserFirstName']} {$this->data['FromUserLastName']} tarafından reddedilmiştir.</p><p>En kısa zamanda tekrar yükleme yapmanızı rica ederiz.</p>";

        return $this->from($this->data['FromUserEmail'], "{$this->data['FromUserFirstName']} {$this->data['FromUserLastName']}")
            ->to($this->data['ToUserEmail'])
            ->subject($subject)
            ->html($text);
    }
}


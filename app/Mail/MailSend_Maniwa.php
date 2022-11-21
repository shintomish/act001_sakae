<?php

namespace App\Mail;

use Illuminate\Support\Facades\Log;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MailSend extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name,  $comment)
    {
        $this->name = $name;
        // $this->email = $email;
        $this->comment = $comment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        Log::info('App_Mail build START');

        //間庭税理士事務所からお知らせがあります。システムをみてください
        // $data["body"] = "間庭税理士事務所からお知らせがあります。システムをみてください";
        
        Log::info('App_Mail build END');

        return $this->view('newsrepo.contact') // どのテンプレートを呼び出すか
                    ->subject('間庭税理士事務所からお知らせ')
                    ->from('system@arkhe-eco.com', '間庭税理士事務所事務局')
                    ->with([
                        'name' => $this->name,
                        'comment' => $this->comment,
                    ]);

    }
}

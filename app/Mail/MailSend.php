<?php

namespace App\Mail;

use Illuminate\Support\Facades\DB;
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
    public function __construct($name,  $comment, $postData)
    {
        $this->name = $name;

        $this->comment = $comment;

        // 2022/11/15
        $this->postData = $postData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        Log::info('App_Mail build START');

        $dbug  = 1;
        $books = DB::table('books')->first();
        $dbug  = $books->price;

        if($dbug == 1){
            $subject   = "株式会社アイゼンテスト税理からお知らせ";
            $fromname  = "株式会社アイゼンテスト税理事務局";
            $fromadr   = "y-shintomi@aizen-sol.co.jp";
            $viewname  = "newsrepo.contact_aizen";
        } else {
            $subject   = "間庭税理士事務所からお知らせ";
            $fromname  = "間庭税理士事務所事務局";
            // $fromadr   = "system@arkhe-eco.com";
            $fromadr   = "y-shintomi@aizen-sol.co.jp";
            $viewname  = "newsrepo.contact_maniwa";
        }

        Log::info('App_Mail build subject  = ' . print_r($subject, true));
        Log::info('App_Mail build END');

        if (isset($this->postData['file'])) {
            $image      = $this->postData['file'];
            $fileName   = $image->getClientOriginalName();
            $filePath   = storage_path('app/public/mail_attachments/');
            $fullPath   = $filePath . $fileName;

            Log::info('App_Mail build  fileName  = ' . print_r($fileName, true));

            return $this->view($viewname) // どのテンプレートを呼び出すか
                        ->subject($subject)
            // ->attachFromStorage($this->postData['filePath'], mb_encode_mimeheader($this->postData['fileName']))
                        ->attach($fullPath)
                        ->from($fromadr, $fromname)
                        ->with([
                            'name'    => $this->name,
                            'comment' => $this->comment,
                        ]);
        } else {
            return $this->view($viewname) // どのテンプレートを呼び出すか
                        ->subject($subject)
                        ->from($fromadr, $fromname)
                        ->with([
                            'name'    => $this->name,
                            'comment' => $this->comment,
                        ]);
        }

    }
}

<?php

namespace App\Services;

use App\Entity\EmailContact;
use Mailjet\Client;
use App\Entity\User;
use Mailjet\Resources;
use App\Entity\EmailModel;

class EmailSender{

    public function sendEmailByMailjet(User $user, EmailContact $email)
    {
    
        $mj = new Client($_ENV['MJ_APIKEY_PUBLIC'], $_ENV['MJ_APIKEY_PRIVATE'],true,['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "sifex@sifood-ci.com",
                        'Name' => "SIFEX"
                        ],
                    'To' => [
                        [
                            'Email' => $user->getEmail(),
                            'Name' =>  $user->getName()
                        ]
                    ],
                    'TemplateID' => 3722625,
                    'TemplateLanguage' => true,
                    'Subject' => $email->getSubject(),
                    'Variables' => [
                        'title' => $email->getTitle(),
                        'content' => $email->getContent()
                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        // $response->success() && dd($response->getData());

    }
}
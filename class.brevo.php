<?php

class fr_brevo{

    public static function trans_email($receiver, $emaildata, $template='', $sender='default'){

        $sender_default = array(
            'preisvorschlag' => array(
                'name' => 'sender name',
                'email' => 'sender@domain.com',
            ),
            'default' => array(
                'name' => 'sender name',
                'email' => 'no-reply@domain.com'
            )
        );

        $url = "https://api.brevo.com/v3/smtp/email";

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array(
            "accept: application/json",
            "api-key: xkeysib-brevo-apikey",
            "content-type: application/json",
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        $sd = $sender_default[$sender];
        ob_start();
        switch($template){
            case 'alternative':
                include TEMPLATEPATH.'/template-parts/tpl/tpl_email_alternative.php';
                break;
            default:
                include TEMPLATEPATH.'/template-parts/tpl/tpl_email_default.php';
                break;
        }
        
        $objC = ob_get_clean();
        $converted = stripslashes($objC);
        $subject   = html_entity_decode($emaildata['subject'], ENT_QUOTES, 'UTF-8');

        $dataArray = [
            "sender" => [
                "name" => $sd['name'],
                "email" => $sd['email']
            ],
            "to" => [
                [
                    "email" => $receiver['email'],
                    "name" => $receiver['name']
                ]
            ],
            "subject" => $subject,
            "htmlContent" => $converted
        ];

        $data = json_encode($dataArray, JSON_UNESCAPED_UNICODE);
        
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        //for debug only!
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $resp = curl_exec($curl);
        curl_close($curl);

    }
}

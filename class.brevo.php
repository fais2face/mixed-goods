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
            case 'preisvorschlag-akzeptieren':
                include TEMPLATEPATH.'/template-parts/tpl/tpl_transactional_preisvorschlag-akzeptieren.php';
                break;
            case 'preisvorschlag-abgelehnt':
                include TEMPLATEPATH.'/template-parts/tpl/tpl_transactional_preisvorschlag-abgelehnt.php';
                break;
            case 'preisvorschlag':
                include TEMPLATEPATH.'/template-parts/tpl/tpl_transactional_preisvorschlag.php';
                break;
            case 'preisvorschlag-verkauft':
                include TEMPLATEPATH.'/template-parts/tpl/tpl_transactional_preisvorschlag-verkauft.php';
                break;
            default:
                include TEMPLATEPATH.'/template-parts/tpl/tpl_transactional_default.php';
                break;
        }
        $objC = ob_get_clean();
        $converted = preg_replace('/\r\n|\r|\n/', '', $objC);
        $converted = str_replace('"', '\"', $converted);
        $data = <<<DATA
        {  
           "sender":{  
              "name":"{$sd['name']}",
              "email":"{$sd['email']}"
           },
           "to":[  
              {  
                 "email":"{$receiver['email']}",
                 "name":"{$receiver['name']}"
              }
           ],
           "subject":"{$emaildata['subject']}",
           "htmlContent":"{$converted}"
        }
        DATA;

        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        //for debug only!
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $resp = curl_exec($curl);
        curl_close($curl);

    }
}

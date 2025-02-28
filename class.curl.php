<?php
class fr_curl {
    private $htCreds = array(
        'user' => 'user',
        'pass' => 'pass'
    );

    function curl_get_contents($url, $credentials = false)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if ($credentials) {
            $username = $this->htCreds['user'];
            $password = $this->htCreds['pass'];
            curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
            curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }


    function curl_post_contents($url, $data)
    {
        $req = curl_init();
        curl_setopt_array($req, [
            CURLOPT_URL => $url,
            CURLOPT_CUSTOMREQUEST => "PUT",
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "content-type: application/json",
            ),
            CURLOPT_RETURNTRANSFER => true,
        ]);

        return curl_exec($req);
    }
}

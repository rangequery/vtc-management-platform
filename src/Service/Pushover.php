<?php

namespace App\Service;

class Pushover
{
    public function callApi($message)
    {
        //Notification PUSH to mobile
        curl_setopt_array($ch = curl_init(), array(
            CURLOPT_URL => "https://api.pushover.net/1/messages.json",
            CURLOPT_POSTFIELDS => array(
                "token" => "ajnkh4j2js2h8gzhcti4hcqeuiuubz",
                "user" => "u49pzbogr2w9cq3g2qomgciisa1hem",
                "message" => $message,
            ),
            CURLOPT_SAFE_UPLOAD => true,
            CURLOPT_RETURNTRANSFER => true,
        ));
        curl_exec($ch);
        curl_close($ch);

    }

}
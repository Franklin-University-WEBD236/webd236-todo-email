<?php
class Email {

  public function test() {
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://api.sendinblue.com/v3/account",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_HTTPHEADER => array(
        "accept: application/json",
        "api-key: " . getenv("EMAIL_API_KEY")
      ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
      die("cURL Error #:" . $err);
    } else {
      die($response);
    }    
  }
  
  public function send($recipientEmail, $subject, $message) {

    $senderEmail = "noreply@" . getenv('PROJECT_DOMAIN') . ".glitch.me";
    $senderName = ucwords(getenv('PROJECT_DOMAIN'), "- ") . " System";

    $params = <<<PARAMS
    {
      "sender":{
        "name":"$senderName",
        "email":"$senderEmail"
      },
      "to":[
        {
          "email":"$recipientEmail"
        }
      ],
      "htmlContent":"$message",
      "subject":"$subject",
      "replyTo":{
        "name":"$senderName",
        "email":"$senderEmail",
      }
    }
PARAMS;
    die("<pre>" . $params . "</pre>");
    $curl = curl_init();
    
    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://api.sendinblue.com/v3/smtp/email",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => $params,
      CURLOPT_HTTPHEADER => array(
        "accept: application/json",
        "content-type: application/json"
      ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
      echo "cURL Error #:" . $err;
    } else {
      echo $response;
    }
  }
}

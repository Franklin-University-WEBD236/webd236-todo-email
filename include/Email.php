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
  
  public function send($email, $subject, $message) {

    $senderEmail = "noreply@" . getenv('PROJECT_DOMAIN');
    
    $curl = curl_init();
    
    $params = <<<PARAMS
{
  "sender":{
    "name":"WEBD 236 Password Reset",
    "email":"$"
  },
  "to":[
    {
      "email":"customer@domain.com"
    }
  ],
  "htmlContent":"<p>HTML content of email</p>",
  "replyTo":{
    "email":"noreply@webd236.com",
    "name":"WEBD 236 Password Reset"
  }
}    
    PARAMS

    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://api.sendinblue.com/v3/smtp/email",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => "{\"sender\":{\"name\":\"WEBD 236 Password Reset\",\"email\":\"noreply@webd236.com\"},\"to\":[{\"email\":\"customer@domain.com\"}],\"htmlContent\":\"<p>HTML content of email</p>\",\"replyTo\":{\"email\":\"noreply@webd236.com\",\"name\":\"WEBD 236 Password Reset\"}}",
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

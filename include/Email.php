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
  
  public function send($recipientEmail, $subject, $message, $simulate=true) {

    $senderEmail = "noreply@" . getenv('PROJECT_DOMAIN') . ".glitch.me";
    $senderName = ucwords(getenv('PROJECT_DOMAIN'), "- ") . " System";
    $message = $simulate ? $message : addslashes($message); // need for JSON
    $params = array(
      "sender" => array(
        "name" => "${senderName}",
        "email" => "${senderEmail}"
      ),
      "to" => array(
        array(
          "email" => "${recipientEmail}"
        )
      ),
      "htmlContent" => "${message}",
      "subject" => "${subject}",
      "replyTo" => array(
        "name" => "${senderName}",
        "email" => "${senderEmail}"
      )
    );
    
    $paramsJSON = json_encode($params, JSON_HEX_QUOT | JSON_HEX_TAG);
/*    
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
        "email":"$senderEmail"
      }
    }
PARAMS;
*/
    if ($simulate) {
      echo $message;
      die();
    }

    $curl = curl_init();
    
    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://api.sendinblue.com/v3/smtp/email",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => $paramsJSON,
      CURLOPT_HTTPHEADER => array(
        "accept: application/json",
        "content-type: application/json",
        "api-key: " . getenv("EMAIL_API_KEY")
      ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
      die("cURL Error #:" . $err);
    }
    Logger::instance()->debug($response);
    return $response;
  }
}

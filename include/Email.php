<?php
class Email {
  
  public function __construct() {
  }
  
  private function makeCurl($endpoint, $method) {
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => $endpoint,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => $method,
      CURLOPT_HTTPHEADER => array(
        "content-type: application/json",
        "accept: application/json",
        "api-key: " . getenv("EMAIL_API_KEY")
      ),
    ));
    return $curl;
  }
  
  public function accountTest() {
    $curl = $this->makeCurl("https://api.sendinblue.com/v3/account", "GET");

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
    
    $paramsJSON = json_encode($params, JSON_HEX_QUOT | JSON_HEX_TAG | JSON_UNESCAPED_SLASHES);

    if ($simulate) {
      echo $message;
      die();
    }

    $curl = $this->makeCurl("https://api.sendinblue.com/v3/smtp/email", "POST");
    curl_setopt($curl, CURLOPT_POSTFIELDS, $paramsJSON);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
      die("cURL Error #:" . $err);
    }
    Logger::instance()->debug($paramsJSON);
    Logger::instance()->debug($response);
    return $response;
  }
}

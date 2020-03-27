<html>
  <body>
    <p>A request was received by {{$_SERVER['SERVER_NAME']}} to reset
      the password associated with this address.</p>
    <p>To reset the password, click <a href='{{$url}}'>here</a> or copy and paste the URL below into a
      web browser.</p>
    <p>{{$url}}</p>
    <p>For your protection, this URL will expire 60 minutes after it
      is generated. Please do not reply to this message. If you have
      received this message in error, please contact
      <a href="mailto:support@{{$_SERVER['SERVER_NAME']}}">support@{{$_SERVER['SERVER_NAME']}}</a>.</p>
  </body>
</html>
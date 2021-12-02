<?php 
 if(empty($_POST['email'])){
   echo json_encode(array("status" => "failed"));
   die();
 }

 if(isset($_POST['email'])){
    $email_to = "niels@vanderschaaf.tech";
    $email_subject = "Portfolio - Contactformulier";

    if(!isset($_POST['name']) || !isset($_POST['email']) || !isset($_POST['message'])) {
     echo json_encode(array("status" => "failed"));
     die();
    } else {
      function clean_string($string) {
        $bad = array("content-type","bcc:","to:","cc:","href");
        return str_replace($bad,"",$string);
      }

      $name = clean_string($_POST['name']);
      $email = clean_string($_POST['email']);
      $message = clean_string($_POST['message']);
		
	  if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	  } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	  } else {
		$ip = $_SERVER['REMOTE_ADDR'];
	  }


      $email_body = "Het contactformulier is ingevuld, de volgende gegevens zijn achter gelaten: \r\n \r\n";
      $email_body.= "Naam: " . $name . "\r\n";
      $email_body.= "Email:" . $email . "\r\n";
	  $email_body.= "IP:" . $ip . "\r\n \r\n";
      $email_body.= "Bericht: \r\n" . $message . "\r\n";

      $headers =
        "From: contact@nielsvdschaaf.nl \r\n" .
        "Reply-To: " . $email . "\r\n" .
        "X-Mailer: PHP/" . phpversion();


	  if (isset($_POST['recaptcha_response'])) {
		// Build POST request:
        $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
        $recaptcha_secret = '6Ld6X5oUAAAAAIGSQTNBpbxiB2fGypfdvTjbSi7n';
        $recaptcha_response = $_POST['recaptcha_response'];

        // Make and decode POST request:
        $recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
        $recaptcha = json_decode($recaptcha);
        // Take action based on the score returned:
        if ($recaptcha->score >= 0.5) {
          mail($email_to, $email_subject, $email_body, $headers);
          echo json_encode(array("status" => "success"));
        } else {
          echo json_encode(array("status" => "failed"));
        }
	  }
	}
 }
?>

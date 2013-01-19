<?php
	require_once('recaptchalib.php');
	  $privatekey = "your private key";
	  $resp = recaptcha_check_answer ($privatekey,
	                                $_SERVER["REMOTE_ADDR"],
	                                $_POST["recaptcha_challenge_field"],
	                                $_POST["recaptcha_response_field"]);

  if (!$resp->is_valid) {
    // What happens when the CAPTCHA was entered incorrectly
    echo "<script>javascript:Recaptcha.reload();</script>";
    die("Sorry, the verification code wasn't entered correctly. Try again.");
  } else {
	  	if(isset($_POST['contactFormSubmitted'])) {
		    // Form submission
			$name = $_POST['name'];
			$email = $_POST['email'];
			$phone = $_POST['phone'];
			$type = $_POST['type'];
			$message = $_POST['message'];

			if (strlen($name) < 3 || strlen($email) < 8 || strlen($message) < 20) {
				echo "<script>javascript:Recaptcha.reload();</script>";
	  			exit("Please ensure you have completed all required fields.");
	  		}

	  		if (!(filter_var($email, FILTER_VALIDATE_EMAIL))) {
	  			echo "<script>javascript:Recaptcha.reload();</script>";
	  			exit("Please ensure you have entered a valid email address.");
	  		}

			$formcontent=" From: $name \n Phone: $phone \n Type: $type \n Message: $message";
			$message = wordwrap($formcontent, 70, "\r\n");
			$recipient = "me@mgakashim.com";
			$subject = "PHAYSE $name for $type";
			$mailheader = "From: $email \r\n";
			mail($recipient, $subject, $message, $mailheader) or die("Something went wrong, please try again or contact us via email instead: contact [at] phayse [dot] com.");
			echo "Thank You! Your message has been sent.";
			echo "<script>$('#inputMessage').val('');</script>";
	  	}
  }
?>

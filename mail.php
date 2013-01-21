<?php
/* Developed by Moka, @ad7863, mgakashim.com, me@mgakashim.com */

	include("EmailAddressValidator.php"); // external class to verify email address
	$validator = new EmailAddressValidator;

	require_once('recaptchalib.php');
	  $privatekey = "your private key"; // enter your private key here
	  $resp = recaptcha_check_answer ($privatekey,
	                                $_SERVER["REMOTE_ADDR"],
	                                $_POST["recaptcha_challenge_field"],
	                                $_POST["recaptcha_response_field"]);

  if (!$resp->is_valid) {
    // What happens when the CAPTCHA was entered incorrectly
    echo "<script>javascript:Recaptcha.reload();</script>"; // reload captcha
    die("Sorry, the verification code wasn't entered correctly. Try again."); // kill program and return error message
  } 

  else {
  	if(isset($_POST['contactFormSubmitted'])) {
	    // Form submission. Feel free to add more , make sure you add validation and add them to the mail line.
		$name = $_POST['name'];
		$email = $_POST['email'];
		$phone = $_POST['phone'];
		$type = $_POST['type'];
		$message = $_POST['message'];
		
		// check length of name, email and message
		if (strlen($name) < 3 || strlen($message) < 20) {
			echo "<script>javascript:Recaptcha.reload();</script>"; // reload the captcha
  			exit("Please ensure you have completed all required fields."); // exit program, return message
  		}
		
		// validate email address
  		if (!($validator->check_email_address($email))) { // if function returns false
            echo "<script>javascript:Recaptcha.reload();</script>"; // reload captcha
  			exit("Please ensure you have entered a valid email address."); // exit program with error message
        } // otherwise carry on
		
		// Build form content
		$formcontent="From: $name\nEmail: $email\nPhone: $phone\nType: $type\nMessage: $message";
		// message should get wordwrapped after 70 chracters? Words?
		$message = wordwrap($formcontent, 70, "\r\n");
		// Enter your email address
		$recipient = "your email address";
		// Enter a subject, only you will see this so make it useful
		$subject = "$name for $type";
		// 'From' mail header
		$mailheader = "From: $email \r\n";
		// Send email, if something goes wrong, kill programm and return error message
		mail($recipient, $subject, $message, $mailheader) or die("Something went wrong, please try again.");
		// If all's well, return success message
		echo "Thank You! Your message has been sent.";
		// ...and clear the message box and reload captcha
		echo "<script>$('#inputMessage').val(''); javascript:Recaptcha.reload();</script>";
  	}
  }
?>

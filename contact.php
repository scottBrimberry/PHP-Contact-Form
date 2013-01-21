<?php 
	// Form session security
	// Start the session
	session_start();
	// Require the class
	require('formkey_class.php');
	// Start the class
	$formKey = new formKey();
	$error = False;
	// Is request?
	if($_SERVER['REQUEST_METHOD'] == 'post')
	{
		// Validate the form key
		if(!isset($_POST['form_key']) || !$formKey->validate())
		{
			// Form key is invalid, show an error
			$error = True;
		}
	}

?>

<!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" lang="en-US" prefix="og: http://ogp.me/ns#">
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" lang="en-US" prefix="og: http://ogp.me/ns#">
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html lang="en-US" prefix="og: http://ogp.me/ns#">
<!--<![endif]-->
	<head>
		<!-- title -->
	    <title>PHP Contact Form</title>

	    <!-- meta and some seo stuff -->
		<meta charset="utf-8" />
	    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	    <meta name="description" content="PHP Contact Form Demonstration" />

	    <!-- html5 shim for ie6-8 support of html5 elements -->
	    <!--[if lt IE 9]>
	      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	    <![endif]-->

	    <!-- stylesheets -->
	    <link href="css/bootstrap.min.css" rel="stylesheet">
	    <link href="css/bootstrap-responsive.min.css" rel="stylesheet">
	    <link href="css/style.css" rel="stylesheet">
	</head>
	<!-- end head, start main body -->

	<body>

		<section id="contact" class="content">
			<div class="container">
				<h2>Contact Form Demo</h2>
				<div class="row">
					<div class="span12 contact-form">
						<p id="formResponse"><?php if($error) { echo($error); } ?></p>
						<form action="mail.php" method="POST" class="form-horizontal contact-form" id="contact-form">
						  <?php $formKey->outputKey(); ?>
						  <fieldset>  
							  <div class="control-group">
							    <label class="control-label" for="inputName">Name</label>
							    <div class="controls">
							      <input class="input-80 required" name="name" type="text" id="inputName">
							    </div>
							  </div>
							  <div class="control-group">
							    <label class="control-label" for="inputEmail">Email</label>
							    <div class="controls">
							      <input class="input-80 required" name="email" type="text" id="inputEmail">
							    </div>
							  </div>
							  <div class="control-group">
							    <label class="control-label" for="inputPhone">Phone</label>
							    <div class="controls">
							      <input class="input-80" name="phone" type="text" id="inputPhone" placeholder="inc. country &amp; area code">
							    </div>
							  </div>
							  <div class="control-group">
							  	<label class="control-label" for="selectSubject">Subject</label>
							  	<div class="controls">
								  	<select class="input-80" id="selectSubject" name="type">
									  <option value="question">Question</option>
									  <option value="support">Support</option>
									  <option value="misc">Comments, complaints, suggestions, other</option>
									</select>
								</div>
							  </div>
							  <div class="control-group">
							    <label class="control-label" for="inputMessage">Message</label>
							    <div class="controls">
							      <textarea class="input-80 required" name="message" rows="12" id="inputMessage" placeholder="Please include as much detail as possible."></textarea>
							    </div>
							  </div>
							  <div class="control-group">
							    <label class="control-label" for="recaptcha">Are you human?</label>
							    <div class="controls" id="recaptcha">
							      <?php
							          require_once('recaptchalib.php');
							          $publickey = ""; // Add your own public key here
							          echo recaptcha_get_html($publickey);
							        ?>
							    </div>
							  </div>
							  <div class="control-group">
							    <div class="controls">
							      <button type="submit" value="Send" class="btn">Send!</button>
							    </div>
							  </div>
							</fieldset>
						</form>
					</div>
				</div>
			</div>
		</section>

		<!-- javascript -->
	    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
	    <script src="js/jquery.validate.min.js"></script>
	    <script>

	    	// contact form validation
	    	$(document).ready(function(){
   
	         $('#contact-form').validate(
		         {
		          rules: {
		            name: {
		              minlength: 3,
		              required: true
		            },
		            email: {
		              required: true,
		              email: true
		            },
		            phone: {
		              minlength: 11,
		              required: false,
		              number: true
		            },
		            subject: {
		              minlength: 3,
		              required: true
		            },
		            message: {
		              minlength: 20,
		              required: true
		            }
		          },
		          highlight: function(label) {
		            $(label).closest('.control-group').addClass('error');
		          },
		          success: function(label) {
		            label
		              .text('OK!').addClass('valid')
		              .closest('.control-group').addClass('success');
		          }
		         });

		    	// contact form submission, clear fields, return message
		    	$("#contact-form").submit(function() {
				    $.post('mail.php', {name: $('#inputName').val(), email: $('#inputEmail').val(), phone: $('#inputPhone').val(), type: $('#selectSubject').val(), message: $('#inputMessage').val(), recaptcha_challenge_field: $('#recaptcha_challenge_field').val(), recaptcha_response_field: $('#recaptcha_response_field').val(), contactFormSubmitted: 'yes'}, function(data) {
				        $("#formResponse").html(data).fadeIn('100');
				        $('#recaptcha_response_field').val(''); /* Clear the inputs */
				    }, 'text');
				    return false;
				});

			}); // end document.ready
	    </script>
	</body>

</html>

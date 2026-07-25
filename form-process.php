<?php

	$errorMSG = "";

    // Helper function to sanitize input
    function clean_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
        return $data;
    }

	// FIRSTNAME
	if (empty($_POST["fname"])) {
		$errorMSG = "First Name is required. ";
	} else {
		$fname = clean_input($_POST["fname"]);
	}

	// LASTNAME
	if (empty($_POST["lname"])) {
		$errorMSG = "Last Name is required. ";
	} else {
		$lname = clean_input($_POST["lname"]);
	}

	// EMAIL
	if (empty($_POST["email"])) {
		$errorMSG .= "Email is required. ";
	} else {
		$email_raw = $_POST["email"];
        // Remove all illegal characters from email
        $email = filter_var($email_raw, FILTER_SANITIZE_EMAIL);
        // Validate e-mail
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errorMSG .= "Invalid email format. ";
        }
	}

	// PHONE
	if (empty($_POST["phone"])) {
		$errorMSG .= "Phone is required. ";
	} else {
		$phone = clean_input($_POST["phone"]);
	}

	// MESSAGE
	if (empty($_POST["message"])) {
		$errorMSG .= "Message is required. ";
	} else {
		$message = clean_input($_POST["message"]);
	}

	$subject = 'Contact Inquiry from We One Removals Website';

	//$EmailTo = "info@yourdomain.com"; // Replace with your email.
	$EmailTo = "enquiry@weoneremovals.com";
	$FromEmail = "no-reply@weoneremovals.com";
	
	// prepare email body text
	$Body = "";
	$Body .= "fname: ";
	$Body .= $fname;
	$Body .= "\n";
	$Body .= "lname: ";
	$Body .= $lname;
	$Body .= "\n";
	$Body .= "Email: ";
	$Body .= $email;
	$Body .= "\n";
	$Body .= "Phone: ";
	$Body .= $phone;
	$Body .= "\n";
	$Body .= "Message: ";
	$Body .= $message;
	$Body .= "\n";

	// send email
    // Prevent Header Injection by removing newlines from headers
    $FromEmail = str_replace(array("\r", "\n"), '', $FromEmail);
    $email = str_replace(array("\r", "\n"), '', $email);

	$headers = "From: {$FromEmail}\r\n";
	$headers .= "Reply-To: {$email}\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

	$success = @mail($EmailTo, $subject, $Body, $headers);

	// redirect to success page
	if ($success && $errorMSG == ""){
	   echo "success";
	}else{
		if($errorMSG == ""){
			echo "Something went wrong :(";
		} else {
			echo $errorMSG;
		}
	}

?>
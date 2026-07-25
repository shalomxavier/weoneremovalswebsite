<?php

$errorMSG = "";

// Helper function to sanitize input
function clean_input($data)
{
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
	return $data;
}

// NAME
if (empty($_POST["name"])) {
	$errorMSG = "Name is required. ";
} else {
	$name = clean_input($_POST["name"]);
}

// EMAIL
if (empty($_POST["email"])) {
	$errorMSG .= "Email is required. ";
} else {
	$email_raw = $_POST["email"];
	$email = filter_var($email_raw, FILTER_SANITIZE_EMAIL);
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

// DATE
if (empty($_POST["date"])) {
	$errorMSG .= "Date is required. ";
} else {
	$date = clean_input($_POST["date"]);
}

// DISTANCE
if (empty($_POST["distance"])) {
	$errorMSG = "distance is required. ";
} else {
	$distance = clean_input($_POST["distance"]);
}

// movetype
if (empty($_POST["movetype"])) {
	$errorMSG = "movetype is required. ";
} else {
	$movetype = clean_input($_POST["movetype"]);
}

// SERVICES
if (empty($_POST["services"])) {
	$errorMSG .= "services is required. ";
} else {
	$services = clean_input($_POST["services"]);
}

$subject = 'Book Appointment from site';

$EmailTo = "chris@weoneremovals.com, mail@jayaraj.pro"; // Replace with your email.
$FromEmail = "no-reply@weoneremovals.com";

// prepare email body text
$Body = "";
$Body .= "name: ";
$Body .= $name;
$Body .= "\n";
$Body .= "Email: ";
$Body .= $email;
$Body .= "\n";
$Body .= "Phone: ";
$Body .= $phone;
$Body .= "\n";
$Body .= "Date: ";
$Body .= $date;
$Body .= "\n";
$Body .= "distance: ";
$Body .= $distance;
$Body .= "\n";
$Body .= "movetype: ";
$Body .= $movetype;
$Body .= "\n";
$Body .= "services: ";
$Body .= $services;
$Body .= "\n";

// send email
// Prevent Header Injection
$FromEmail = str_replace(array("\r", "\n"), '', $FromEmail);
$email = str_replace(array("\r", "\n"), '', $email);

$headers = "From: {$FromEmail}\r\n";
$headers .= "Reply-To: {$email}\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

$success = @mail($EmailTo, $subject, $Body, $headers);

// redirect to success page
if ($success && $errorMSG == "") {
	echo "success";
} else {
	if ($errorMSG == "") {
		echo "Something went wrong :(";
	} else {
		echo $errorMSG;
	}
}

?>
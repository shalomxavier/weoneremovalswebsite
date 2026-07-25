<?php

$errorMSG = "";

// Helper function to sanitize input
function clean_input($data)
{
    if ($data === null)
        return "";
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

// LOCATION
if (empty($_POST["location"])) {
    $errorMSG .= "Location is required. ";
} else {
    $location = clean_input($_POST["location"]);
}

// CLEARANCE TYPE
if (empty($_POST["clearancetype"])) {
    $errorMSG .= "Clearance type is required. ";
} else {
    $clearancetype = clean_input($_POST["clearancetype"]);
}

// MESSAGE
$message = clean_input($_POST["message"]);

$subject = 'Form Submission_Clearances Landing Page - We One Removals';

$EmailTo = "chris@weoneremovals.com, mail@jayaraj.pro";
$FromEmail = "no-reply@weoneremovals.com";

// prepare email body text
$Body = "";
$Body .= "Name: " . $name . "\n";
$Body .= "Email: " . $email . "\n";
$Body .= "Phone: " . $phone . "\n";
$Body .= "Location: " . $location . "\n";
$Body .= "Clearance Type: " . $clearancetype . "\n";
$Body .= "Message: " . $message . "\n";

// send email
$FromEmail = str_replace(array("\r", "\n"), '', $FromEmail);
$email = str_replace(array("\r", "\n"), '', $email);

$headers = "From: {$FromEmail}\r\n";
$headers .= "Reply-To: {$email}\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

if ($errorMSG == "") {
    $success = @mail($EmailTo, $subject, $Body, $headers);
} else {
    $success = false;
}

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
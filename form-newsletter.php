<?php

$errorMSG = "";

if (empty($_POST["mail"])) {
    $errorMSG = "Email is required. ";
} else {
    $email_raw = $_POST["mail"];
    $email = filter_var($email_raw, FILTER_SANITIZE_EMAIL);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMSG = "Invalid email address. ";
    }
}

$subject = 'Newsletter Subscription Request';

$EmailTo = "enquiry@weoneremovals.com";
$FromEmail = "no-reply@weoneremovals.com";

// Clean body content specifically isn't needed as much here since it's just the email, 
// but good practice to allow execution without injection.
$Body = "Newsletter subscription request\n";
$Body .= "Email: ";
$Body .= $email ?? '';
$Body .= "\n";

// Prevent Header Injection
$FromEmail = str_replace(array("\r", "\n"), '', $FromEmail);
if (isset($email)) {
    $email = str_replace(array("\r", "\n"), '', $email);
}

$headers = "From: {$FromEmail}\r\n";
if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $headers .= "Reply-To: {$email}\r\n";
}
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

// Only send if no errors
if ($errorMSG == "") {
    $success = mail($EmailTo, $subject, $Body, $headers);
} else {
    $success = false;
}

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
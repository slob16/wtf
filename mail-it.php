<?php

// Configurable
$send_to = "info@wetalentedfew.com";
$send_subject = "WTF Run wild - Contact Form Submission";

// Utility function to sanitize input
function cleanupentries($entry) {
    return htmlspecialchars(stripslashes(trim($entry)), ENT_QUOTES, 'UTF-8');
}

// Check if all required POST fields exist
if (
    !isset($_POST['name']) ||
    !isset($_POST['email']) ||
    !isset($_POST['message'])
) {
    echo "Missing required fields";
    exit;
}

$f_name = cleanupentries($_POST['name']);
$f_email = cleanupentries($_POST['email']);
$f_message = cleanupentries($_POST['message']);
$from_ip = $_SERVER['REMOTE_ADDR'] ?? 'Unknown IP';
$from_browser = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown Browser';

// Validate fields
if (empty($f_name)) {
    echo "no name";
    exit;
}

if (empty($f_email)) {
    echo "no email";
    exit;
}

if (!filter_var($f_email, FILTER_VALIDATE_EMAIL)) {
    echo "invalid email";
    exit;
}

if (empty($f_message)) {
    echo "no message";
    exit;
}

// Prepare email
$date = date('m-d-Y');
$message = <<<EOT
This email was submitted on $date

Name: $f_name

E-Mail: $f_email

Message:
$f_message

Technical Details:
IP: $from_ip
Browser: $from_browser
EOT;

$full_subject = $send_subject . " - {$f_name}";

// Additional headers
$headers = "From: " . $f_email . "\r\n";
$headers .= "Reply-To: " . $f_email . "\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();

// Send the email
if (mail($send_to, $full_subject, $message, $headers)) {
    echo "true";
} else {
    echo "Failed to send email.";
}

?>

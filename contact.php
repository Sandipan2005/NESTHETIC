<?php
// contact.php

// 1. Check if the request is a POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 2. Collect and sanitize input data
    $name = strip_tags(trim($_POST["name"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $message = strip_tags(trim($_POST["message"]));

    // 3. Validation
    // Check if fields are empty or email is invalid
    if (empty($name) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Redirect back with error
        header("Location: index.html?status=error");
        exit;
    }

    // 4. Configure Email
    $recipient = "nesthetic90@gmail.com";
    $subject = "New Contact from NESTHETIC Website: $name";
    
    // Build the email content
    $email_content = "Name: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Message:\n$message\n";

    // Build the email headers
    $email_headers = "From: $name <$email>";

    // 5. Send the email
    if (mail($recipient, $subject, $email_content, $email_headers)) {
        // Redirect to success
        header("Location: index.html?status=success");
    } else {
        // Redirect to error
        header("Location: index.html?status=error");
    }

} else {
    // If someone tries to access this file directly without POST, go back to home
    header("Location: index.html");
    exit;
}
?>
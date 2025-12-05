<?php
// contact.php - NESTHETIC Contact Form Handler

// Prevent direct access and handle errors
error_reporting(0);

// Set base URL - Change this to your actual domain
$base_url = "https://www.nesthetic.in/";

// 1. Check if the request is a POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 2. Collect and sanitize input data
    $name = isset($_POST["name"]) ? strip_tags(trim($_POST["name"])) : "";
    $email = isset($_POST["email"]) ? filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL) : "";
    $message = isset($_POST["message"]) ? strip_tags(trim($_POST["message"])) : "";

    // 3. Validation
    if (empty($name) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: " . $base_url . "index.html?status=error&reason=validation");
        exit;
    }

    // 4. Configure Email
    $recipient = "nesthetic90@gmail.com";
    $subject = "New Contact from NESTHETIC Website: " . $name;
    
    // Build the email content
    $email_content = "You have received a new message from your website contact form.\n\n";
    $email_content .= "Name: " . $name . "\n";
    $email_content .= "Email: " . $email . "\n\n";
    $email_content .= "Message:\n" . $message . "\n";
    $email_content .= "\n---\nSent from NESTHETIC Website Contact Form";

    // Build proper email headers
    $email_headers = "MIME-Version: 1.0\r\n";
    $email_headers .= "Content-type: text/plain; charset=UTF-8\r\n";
    $email_headers .= "From: NESTHETIC Website <noreply@nesthetic.in>\r\n";
    $email_headers .= "Reply-To: " . $name . " <" . $email . ">\r\n";
    $email_headers .= "X-Mailer: PHP/" . phpversion();

    // 5. Send the email
    if (mail($recipient, $subject, $email_content, $email_headers)) {
        header("Location: " . $base_url . "index.html?status=success");
        exit;
    } else {
        header("Location: " . $base_url . "index.html?status=error&reason=mail");
        exit;
    }

} else {
    // If someone tries to access this file directly without POST, go back to home
    header("Location: " . $base_url . "index.html");
    exit;
}
?>
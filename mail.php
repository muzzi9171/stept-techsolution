<?php

use PHPMailer\PHPMailer\PHPMailer;

require('PHPMailer/src/Exception.php');
require('PHPMailer/src/PHPMailer.php');
require('PHPMailer/src/SMTP.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    # FIX: Replace this email with recipient email
    $mail_to = "haseebafandil@gmail.com";
    # Sender Data
    if (empty($_POST["subject"]) || empty($_POST["name"]) || empty($_POST["email"]) || empty($_POST["message"])) {

        echo "Please complete the form and try again.";

    } else {
        $subject = trim($_POST["subject"]);
        $name = str_replace(array("\r", "\n"), array(" ", " "), strip_tags(trim($_POST["name"])));
        $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
        $message = trim($_POST["message"]);

        if (empty($name) or !filter_var($email, FILTER_VALIDATE_EMAIL) or empty($subject) or empty($message)) {
            # Set a 400 (bad request) response code and exit.
            http_response_code(400);
            echo "Please complete the form and try again.";
            exit;
        }


        $mail = new PHPMailer(true);
        $mail->IsSMTP();
        $mail->Host = 'smtp.gmail.com.';
        $mail->SMTPAuth = true;
        $mail->Username = 'Steptechsolution7@gmail.com';
        $mail->Password = 'Amber@123';
        $mail->SMTPSecure = 'SSL';
        $mail->Port = 465;
        $mail->setFrom($email, $name);
        $mail->addAddress($mail_to, '$username');
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = '<p>From: ' . $name . '</p>
          <p>E-mail: ' . $email . '</p>
          <p>Subject: ' . $subject . '</p>
          <p>Message: ' . $message . '</p>';


        $success = $mail->send();

        # Mail Content
        // $content = "Name: $name\n";
        // $content = "Topic: $subject\n";
        // $content .= "Email: $email\n\n";
        // $content .= "Message:\n$message\n";

        // # email headers.
        // $headers = "From: $name <$email>";

        // # Send the email.
        // $success = mail($mail_to, $content, $headers);
        if ($success) {
            # Set a 200 (okay) response code.
            http_response_code(200);
            echo "Thank You! Your message has been sent.";
        } else {
            # Set a 500 (internal server error) response code.
            http_response_code(500);
            echo "Oops! Something went wrong, we couldn't send your message.";
        }
    }

} else {
    # Not a POST request, set a 403 (forbidden) response code.
    http_response_code(403);
    echo "There was a problem with your submission, please try again.";
}

?>
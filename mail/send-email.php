<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer.php';
require 'SMTP.php';
require 'Exception.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $name = $data['name'];
    $email = $data['email'];
    $message = $data['message'];
    $company_name = $data['company_name'];

    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'ceo.lakshinvestments@gmail.com'; // Your Gmail address
        $mail->Password   = 'dhoyrjfiazvaiyvy';        // Your Gmail password or App Password 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom($email, $name);
        $mail->addAddress('ceo.lakshinvestments@gmail.com', 'Laksh Investments'); // Your Gmail address

        // Content
        $mail->isHTML(true);
        $mail->Subject = "{$name} tried to contact {$company_name}";
        $mail->Body = "
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e0e0e0; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);'>
                    <h2 style='background-color: #0c356a; padding: 10px; border-radius: 4px; text-align: center; color:white'>Contact From {$company_name}</h2>
                    <p style='font-size: 16px;'><strong>Name:</strong> {$name}</p>
                    <p style='font-size: 16px;'><strong>Email:</strong> {$email}</p>
                    <p style='font-size: 16px;'><strong>Message:</strong></p>
                    <div style='background-color: #f9f9f9; padding: 15px; border-radius: 6px; font-size: 16px;'>{$message}</div>
                </div>";

        $mail->send();
        echo json_encode(["status" => "success", "message" => "Email sent!"]);
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => "Message could not be sent. Mailer Error: {$mail->ErrorInfo}"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}

<?php
include('conn/conn.php');
session_start();
require_once "vendor/autoload.php";
use Twilio\Rest\Client;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);


$sid = 'AC03c42099a6a57d7e417eda4ef05fbc72';
$token = 'f2a5c9bb39a377310e663d85c807261d';
$phoneNumber = "+15073997077";
$twilio = new Client($sid, $token);


$logFile = 'notifications_log.txt';


function writeLog($message)
{
    global $logFile;
    $timeStamp = date('Y-m-d H:i:s');
    file_put_contents($logFile, "[$timeStamp] $message" . PHP_EOL, FILE_APPEND);
}

writeLog("Cron job started.");


$stmt = $conn->prepare("
    SELECT 
        n.id,
        n.title, 
        n.description, 
        n.date, 
        u.email, 
        u.contact_number 
    FROM 
        tbl_notifiction n 
    JOIN 
        tbl_user u 
    ON 
        n.user_id = u.id
");
$stmt->execute();
$results = $stmt->fetchAll();

writeLog("Fetched " . count($results) . " notifications from the database.");

// Set timezone to Pakistan Standard Time (Asia/Karachi)
date_default_timezone_set('Asia/Karachi');

// Get current date and time in Pakistan
$current_date_time = date('Y-m-d\TH:i');

foreach ($results as $result) {
    $notification_id = $result['id'];
    $title = $result['title'];
    $description = $result['description'];
    $date = $result['date'];
    $email = $result['email'];

    $phone_number = $result['contact_number'];

    if ($current_date_time == $date) {
        try {
            $mail->SMTPDebug = 2;
            $mail->isSMTP();
            $mail->Host = 'smtp.hostinger.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'no-reply@4poch.com';
            $mail->Password = '#123456Aa';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            // Recipients
            $mail->setFrom('no-reply@4poch.com', 'Reminder');
            $mail->addAddress($email, 'User');
            $mail->addReplyTo('no-reply@4poch.com', 'Information');
            $mail->isHTML(true);
            $mail->Subject = 'Reminder for ' . $title;
            $mail->Body = '<h1>' . $title . '</h1><p>' . $description . '</p>';
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
            writeLog("Email sent to: $email for notification ID: $notification_id");

            try {
                $message = $twilio->messages->create(
                    $phone_number,
                    [
                        "body" => "$title - $description",
                        "from" => $phoneNumber
                    ]
                );
                writeLog("SMS sent to: $phone_number for notification ID: $notification_id. Message SID: " . $message->sid);
            } catch (Exception $e) {
                writeLog("Error sending SMS to: $phone_number. Error: {$e->getMessage()}");
            }

        } catch (Exception $e) {
            writeLog("Error sending email to: $email. Error: {$mail->ErrorInfo}");
        }

        // Update the notification status to 1
        $updateStmt = $conn->prepare("UPDATE tbl_notifiction SET status = 1 WHERE id = :id");
        $updateStmt->bindParam(':id', $notification_id);
        $updateStmt->execute();
        writeLog("Notification status updated to 1 for ID: $notification_id");

    } else {
        writeLog("Current time does not match notification time for ID: $notification_id");
    }
}

writeLog("Cron job completed.");
?>
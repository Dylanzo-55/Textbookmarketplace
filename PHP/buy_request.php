<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '../vendor/autoload.php'; // Adjust path if needed

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $seller_username = $_POST['seller_username'];
    $title = $_POST['title'];
    $price = $_POST['price'];
    $buyer_username = $_POST['buyer_username'];

    // Get buyer email from session or database
    $buyer_email = '';
    if (isset($_SESSION['username']) && $_SESSION['username'] === $buyer_username) {
        // Fetch buyer email from database
        $host = 'localhost';
        $dbusername = 'root';
        $dbpassword = '';
        $dbname = 'textbookmarketplacedb';
        $conn = new mysqli($host, $dbusername, $dbpassword, $dbname);
        $stmt = $conn->prepare("SELECT email FROM login WHERE username=?");
        $stmt->bind_param("s", $buyer_username);
        $stmt->execute();
        $stmt->bind_result($buyer_email);
        $stmt->fetch();
        $stmt->close();
    }

    // Get seller email from database
    $seller_email = '';
    $host = 'localhost';
    $dbusername = 'root';
    $dbpassword = '';
    $dbname = 'textbookmarketplacedb';
    $conn = new mysqli($host, $dbusername, $dbpassword, $dbname);
    $stmt = $conn->prepare("SELECT email FROM login WHERE username=?");
    $stmt->bind_param("s", $seller_username);
    $stmt->execute();
    $stmt->bind_result($seller_email);
    $stmt->fetch();
    $stmt->close();
    $conn->close();

    if ($seller_email && $buyer_email) {
        // Prepare email details
        $subject = 'New Buy Request';
        $body = "Hello,<br><br>You have received a new buy request for your textbook listing.<br><br>Details:<br>Buyer: $buyer_username<br>Email: $buyer_email<br><br>TextbookMarketPlace Team";

        if ($_SERVER['SERVER_NAME'] === 'your-infinityfree-domain.epizy.com') {
            // Use mail() on InfinityFree
            $headers = "From: Textbook Marketplace <TextbookMarketPlace34@gmail.com>\r\n";
            $headers .= "Reply-To: TextbookMarketPlace34@gmail.com\r\n";
            $headers .= "Content-type: text/html; charset=UTF-8\r\n";
            mail($seller_email, $subject, $body, $headers);
        } else {
            // Use PHPMailer for other hosts
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'TextbookMarketPlace34@gmail.com'; // your email
                $mail->Password   = 'vxih anne dguv yqyw'; // your app password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;

                $mail->setFrom('TextbookMarketPlace34@gmail.com', 'Textbook Marketplace');
                $mail->addAddress($seller_email, $seller_username);

                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body    = $body;

                $mail->send();
            } catch (Exception $e) {
                echo "<script>alert('Mailer Error: {$mail->ErrorInfo}'); window.location.href='../HTML/buy.html';</script>"; // <-- Fix path
                exit();
            }
        }

        // Let the user know
        echo "<script>alert('The seller has been notified by email!'); window.location.href='../HTML/buy.html';</script>"; // <-- Fix path
        exit();
    } else {
        echo "<script>alert('Could not find seller or buyer email.'); window.location.href='../HTML/buy.html';</script>"; // <-- Fix path
        exit();
    }
}
?>
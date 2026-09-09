<?php
    session_start();

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require '../vendor/autoload.php'; // If you used Composer

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $university = $_POST['university'];
        $password = $_POST['password'];

        // Check if username already exists
        $host = 'localhost';
        $dbusername = 'root';
        $dbpassword = '';
        $dbname = 'textbookmarketplacedb';
        $conn = new mysqli($host, $dbusername, $dbpassword, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $query = "SELECT * FROM login WHERE username='$username'";
        $result = $conn->query($query);
        if ($result->num_rows > 0) {
            header("Location: ../index.html?error=Username already exists");
            exit();
        }

        // Generate verification code
        $verification_code = rand(100000, 999999);

        // Store user info and code in session
        $_SESSION['pending_user'] = [
            'username' => $username,
            'email' => $email,
            'university' => $university,
            'password' => $password,
            'code' => $verification_code
        ];

        // Send email with PHPMailer or mail()
        $subject = 'Your Verification Code';
        $body = "Welcome to Textbookmarketplace<br>Your verification code is: <b>$verification_code</b><br>Please verify your account.<br>If you did not register, please ignore this email.<br>Textbookmarketplace Team";

        if ($_SERVER['SERVER_NAME'] === 'your-infinityfree-domain.epizy.com') {
            // Use mail() on InfinityFree
            $headers = "From: Textbook Marketplace <TextbookMarketPlace34@gmail.com>\r\n";
            $headers .= "Reply-To: TextbookMarketPlace34@gmail.com\r\n";
            $headers .= "Content-type: text/html; charset=UTF-8\r\n";
            mail($email, $subject, $body, $headers);
        } else {
            // Use PHPMailer for other hosts
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'TextbookMarketPlace34@gmail.com';
                $mail->Password   = 'vxih anne dguv yqyw';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;

                $mail->setFrom('TextbookMarketPlace34@gmail.com', 'Textbook Marketplace');
                $mail->addAddress($email, $username);

                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body    = $body;

                $mail->send();
            } catch (Exception $e) {
                echo "Mailer Error: {$mail->ErrorInfo}";
                exit();
            }
        }

        header("Location: ../HTML/verify.html");
        exit();
    }
?>
<?php
// index.php

$errors = [];
$successMsg = '';
$errorMsg = '';

$name = '';
$email = '';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($name === '') $errors[] = 'Name is required.';
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'A valid email is required.';
    if ($message === '') $errors[] = 'Message is required.';

    if (!$errors) {
        $to = "r.vanleeuwen@graafschapcollege.nl"; // <-- vervang dit
        $subject = "New Contact Form Submission";

        $body = "Name: {$name}\n"
              . "Email: {$email}\n"
              . "Message:\n{$message}\n";

        // Let op: veel hosts accepteren geen "From" met willekeurige afzenders.
        // Gebruik daarom een fixed From op jouw domein, en zet de gebruiker in Reply-To.
        $from = "no-reply@graafschapcollege.nl"; // <-- vervang dit naar iets op jouw domein

        $headers = [];
        $headers[] = "From: {$from}";
        $headers[] = "Reply-To: {$email}";
        $headers[] = "Content-Type: text/plain; charset=UTF-8";

        if (mail($to, $subject, $body, implode("\r\n", $headers))) {
            $successMsg = "Email sent successfully!";
            // formulier leegmaken na succes
            $name = $email = $message = '';
        } else {
            $errorMsg = "Failed to send email.";
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Contact Form</title>
  <style>
    body { font-family: Arial, sans-serif; }
    .box { width: 260px; }
    label { display:block; margin: 10px 0 4px; font-size: 13px; }
    input[type="text"], textarea { width: 220px; padding: 4px; }
    textarea { height: 70px; resize: vertical; }
    .msg { margin: 10px 0; padding: 8px; border: 1px solid #ccc; }
    .error { border-color: #c00; color: #c00; }
    .success { border-color: #090; color: #060; }
    button { margin-top: 10px; padding: 4px 10px; }
  </style>
</head>
<body>
  <div class="box">
    <h3>Contact Form</h3>

    <?php if ($successMsg): ?>
      <div class="msg success"><?= htmlspecialchars($successMsg, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <?php if ($errorMsg): ?>
      <div class="msg error"><?= htmlspecialchars($errorMsg, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <?php if ($errors): ?>
      <div class="msg error">
        <ul style="margin:0; padding-left:18px;">
          <?php foreach ($errors as $e): ?>
            <li><?= htmlspecialchars($e, ENT_QUOTES, 'UTF-8') ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <form method="post" action="">
      <label for="name">Name:</label>
      <input id="name" name="name" type="text"
             value="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>">

      <label for="email">Email:</label>
      <input id="email" name="email" type="text"
             value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>">

      <label for="message">Message:</label>
      <textarea id="message" name="message"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></textarea>

      <button type="submit">Send</button>
    </form>
  </div>
</body>
</html>

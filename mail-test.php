<?php
/**
 * Local Mailpit test page for Docker/WSL.
 * Open /mail-test.php, send a test message, then check Mailpit.
 */

$to = filter_input(INPUT_POST, 'to', FILTER_VALIDATE_EMAIL) ?: 'test@example.local';
$sent = null;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject = 'LPCMS Docker PHP mail() test - ' . date('Y-m-d H:i:s');
    $body = "This is a local PHP mail() test from LPCMS Docker.\n\n" .
            "Host: " . ($_SERVER['HTTP_HOST'] ?? 'localhost') . "\n" .
            "Time: " . date('c') . "\n";
    $headers = "MIME-Version: 1.0\r\n" .
               "Content-Type: text/plain; charset=UTF-8\r\n" .
               "From: no-reply@" . preg_replace('/^www\./', '', $_SERVER['HTTP_HOST'] ?? 'localhost') . "\r\n";
    $sent = mail($to, $subject, $body, $headers);
    if (!$sent) {
        $error = 'mail() returned false. Check: docker compose logs php';
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>LPCMS Mail Test</title>
  <style>
    body { font-family: system-ui, -apple-system, Segoe UI, sans-serif; max-width: 760px; margin: 3rem auto; padding: 0 1rem; line-height: 1.5; }
    input, button { font: inherit; padding: .65rem .8rem; }
    input { min-width: min(100%, 360px); }
    button { cursor: pointer; }
    .ok { background: #e8fff1; border: 1px solid #9be3b4; padding: 1rem; }
    .bad { background: #fff0f0; border: 1px solid #f0aaaa; padding: 1rem; }
    code { background: #f3f3f3; padding: .1rem .25rem; }
  </style>
</head>
<body>
  <h1>LPCMS Docker PHP mail() test</h1>
  <p>This sends through <code>mail()</code> to Mailpit inside Docker. It does not send real internet email.</p>

  <?php if ($sent === true): ?>
    <p class="ok">Message accepted by <code>mail()</code>. Open Mailpit to view it.</p>
  <?php elseif ($sent === false): ?>
    <p class="bad"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
  <?php endif; ?>

  <form method="post">
    <label for="to">To address</label><br>
    <input id="to" name="to" type="email" value="<?= htmlspecialchars($to, ENT_QUOTES, 'UTF-8') ?>" required>
    <button type="submit">Send test mail</button>
  </form>

  <h2>Where to check</h2>
  <p>Direct Mailpit URL: <a href="http://localhost:8025">http://localhost:8025</a></p>
  <p>Proxy Mailpit URL: <a href="http://mail.lpcms.localhost">http://mail.lpcms.localhost</a></p>
</body>
</html>

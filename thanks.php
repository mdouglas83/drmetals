<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';

$siteName = (string) cfg($config, ['site', 'name'], 'DR Metals');
$status = isset($_GET['status']) ? (string) $_GET['status'] : 'sent';

$messages = [
    'sent' => (string) cfg($config, ['form', 'success_message'], 'Thanks. We received your request and will follow up soon.'),
    'stored' => (string) cfg($config, ['form', 'fallback_message'], 'Thanks. Your request was saved locally because outgoing email is not configured yet.')
];

$detail = $messages[$status] ?? $messages['sent'];
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Request Received | <?php echo e($siteName); ?></title>
    <meta name="robots" content="noindex" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap"
      rel="stylesheet"
    />
    <link rel="icon" href="favicon.ico" sizes="any" />
    <link rel="icon" type="image/png" href="favicon.png" />
    <link rel="shortcut icon" href="favicon.ico" />
    <link rel="stylesheet" href="assets/css/site.css" />
  </head>
  <body class="thanks-page">
    <main class="thanks-wrap">
      <p class="eyebrow">Request Received</p>
      <h1>Thanks. We have enough to begin.</h1>
      <p><?php echo e($detail); ?></p>
      <a class="button button-primary" href="index.php">Return to <?php echo e($siteName); ?></a>
    </main>
  </body>
</html>

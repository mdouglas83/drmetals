<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';

function clean_value(string $value, int $limit = 2000): string
{
    $value = trim($value);
    $value = str_replace(["\r", "\n"], ' ', $value);
    return function_exists('mb_substr') ? mb_substr($value, 0, $limit) : substr($value, 0, $limit);
}

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    header('Location: index.php#contact', true, 302);
    exit;
}

if (!empty($_POST['website'] ?? '')) {
    $thanksPage = (string) cfg($config, ['form', 'thanks_page'], 'thanks.php');
    header('Location: ' . $thanksPage, true, 302);
    exit;
}

$firstName = clean_value((string) ($_POST['first_name'] ?? ''), 100);
$lastName = clean_value((string) ($_POST['last_name'] ?? ''), 100);
$company = clean_value((string) ($_POST['company'] ?? ''), 160);
$email = clean_value((string) ($_POST['email'] ?? ''), 160);
$phone = clean_value((string) ($_POST['phone'] ?? ''), 80);
$message = trim((string) ($_POST['message'] ?? ''));
$message = function_exists('mb_substr') ? mb_substr($message, 0, 5000) : substr($message, 0, 5000);

if ($firstName === '' || $lastName === '' || $message === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: index.php?status=error#contact', true, 302);
    exit;
}

$siteName = (string) cfg($config, ['site', 'name'], 'DR Metals');
$recipient = (string) cfg($config, ['mail', 'to'], cfg($config, ['site', 'contact', 'lead_recipient'], 'team@drmetals.com'));
$subjectPrefix = (string) cfg($config, ['mail', 'subject_prefix'], 'DR Metals Website Lead');
$mailEnabled = (bool) cfg($config, ['mail', 'enabled'], false);
$fromAddress = (string) cfg($config, ['mail', 'from'], 'website@drmetals.com');
$thanksPage = (string) cfg($config, ['form', 'thanks_page'], 'thanks.php');

$name = trim($firstName . ' ' . $lastName);
$subjectName = $company !== '' ? $company : $name;
$subject = $subjectPrefix . ' | ' . $subjectName;

$body = "New {$siteName} website request\n\n";
$body .= "Name: {$name}\n";
$body .= "Company: " . ($company !== '' ? $company : '(none)') . "\n";
$body .= "Email: {$email}\n";
$body .= "Phone: " . ($phone !== '' ? $phone : '(none)') . "\n\n";
$body .= "Message:\n{$message}\n";

$headers = [
    'From: ' . $siteName . ' Website <' . $fromAddress . '>',
    'Reply-To: ' . $name . ' <' . $email . '>',
    'Content-Type: text/plain; charset=UTF-8'
];

$sent = false;

if ($mailEnabled) {
    $sent = @mail($recipient, $subject, $body, implode("\r\n", $headers));
}

if ($sent) {
    header('Location: ' . $thanksPage . '?status=sent', true, 302);
    exit;
}

$storageDir = __DIR__ . '/storage';
if (!is_dir($storageDir)) {
    mkdir($storageDir, 0775, true);
}

$logEntry = json_encode([
    'submitted_at' => gmdate('c'),
    'first_name' => $firstName,
    'last_name' => $lastName,
    'company' => $company,
    'email' => $email,
    'phone' => $phone,
    'message' => $message
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

$saved = $logEntry !== false
    ? file_put_contents($storageDir . '/inquiries.log', $logEntry . PHP_EOL, FILE_APPEND | LOCK_EX)
    : false;

if ($saved !== false) {
    header('Location: ' . $thanksPage . '?status=stored', true, 302);
    exit;
}

header('Location: index.php?status=error#contact', true, 302);
exit;

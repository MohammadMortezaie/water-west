<?php
declare(strict_types=1);

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    header('Location: /contact', true, 303);
    exit;
}

function clean_field(string $key, int $maxLength = 500): string
{
    $value = trim((string) ($_POST[$key] ?? ''));
    $value = str_replace(["\r", "\0"], '', $value);
    return mb_substr($value, 0, $maxLength);
}

$name = clean_field('name', 100);
$phone = clean_field('phone', 40);
$email = clean_field('email', 150);
$message = clean_field('message', 2000);

$emailValid = (bool) filter_var($email, FILTER_VALIDATE_EMAIL);
$phoneValid = (bool) preg_match('/^[0-9+().\-\s]{7,40}$/', $phone);

if ($name === '' || !$phoneValid || !$emailValid || $message === '') {
    header('Location: /contact?error=1', true, 303);
    exit;
}

$subject = 'Free inspection request from website';
$body = implode("\n", [
    'New water damage service request',
    '',
    'Name: ' . $name,
    'Phone: ' . $phone,
    'Email: ' . $email,
    '',
    'Message:',
    $message,
]);

$headers = [
    'From: Website Request <no-reply@waterdamagewestvancouver.ca>',
    'Content-Type: text/plain; charset=UTF-8',
];
$headers[] = 'Reply-To: ' . $email;

$sent = @mail('info@waterdamagewestvancouver.ca', $subject, $body, implode("\r\n", $headers));
header('Location: /contact?' . ($sent ? 'sent=1' : 'error=1'), true, 303);
exit;

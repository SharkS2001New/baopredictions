<?php

namespace App\Services;

/**
 * Contact form mailer — SMTP when configured, otherwise PHP mail().
 */
final class ContactMailService
{
    /**
     * @param  array{name: string, email: string, subject: string, message: string}  $payload
     * @return array{ok: bool, error?: string}
     */
    public function send(array $payload): array
    {
        $name = $this->oneLine($payload['name'] ?? '', 120);
        $email = $this->oneLine($payload['email'] ?? '', 180);
        $subject = $this->oneLine($payload['subject'] ?? 'Website enquiry', 160);
        $message = trim((string) ($payload['message'] ?? ''));

        if ($name === '' || $email === '' || $message === '') {
            return ['ok' => false, 'error' => 'Please fill in your name, email, and message.'];
        }
        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['ok' => false, 'error' => 'Please enter a valid email address.'];
        }
        if (mb_strlen($message) < 10) {
            return ['ok' => false, 'error' => 'Please write a slightly longer message (at least 10 characters).'];
        }
        if (mb_strlen($message) > 5000) {
            return ['ok' => false, 'error' => 'Message is too long (max 5,000 characters).'];
        }

        $to = $this->oneLine((string) bao_env('CONTACT_TO_EMAIL', 'hello@baopredictions.com'), 180);
        if ($to === '' || ! filter_var($to, FILTER_VALIDATE_EMAIL)) {
            return ['ok' => false, 'error' => 'Contact inbox is not configured. Please try again later.'];
        }

        $fromAddress = $this->oneLine(
            (string) bao_env('MAIL_FROM_ADDRESS', 'noreply@baopredictions.com'),
            180
        );
        $fromName = $this->oneLine((string) bao_env('MAIL_FROM_NAME', 'Bao Predictions'), 80);
        if ($fromAddress === '' || ! filter_var($fromAddress, FILTER_VALIDATE_EMAIL)) {
            $fromAddress = 'noreply@baopredictions.com';
        }

        $mailSubject = '[Bao Contact] ' . $subject;
        $body = "New message from the Bao Predictions contact form\n\n"
            . "Name: {$name}\n"
            . "Email: {$email}\n"
            . "Subject: {$subject}\n"
            . 'Submitted: ' . gmdate('Y-m-d H:i:s') . " UTC\n"
            . "IP: " . $this->clientIp() . "\n\n"
            . "Message:\n{$message}\n";

        $host = trim((string) bao_env('MAIL_HOST', ''));
        if ($host !== '') {
            return $this->sendSmtp(
                $host,
                $to,
                $fromAddress,
                $fromName,
                $email,
                $name,
                $mailSubject,
                $body
            );
        }

        return $this->sendPhpMail($to, $fromAddress, $fromName, $email, $name, $mailSubject, $body);
    }

    /**
     * @return array{ok: bool, error?: string}
     */
    private function sendPhpMail(
        string $to,
        string $fromAddress,
        string $fromName,
        string $replyEmail,
        string $replyName,
        string $subject,
        string $body
    ): array {
        $headers = [
            'MIME-Version: 1.0',
            'Content-Type: text/plain; charset=UTF-8',
            'From: ' . $this->formatAddress($fromName, $fromAddress),
            'Reply-To: ' . $this->formatAddress($replyName, $replyEmail),
            'X-Mailer: BaoPredictions-Contact/1.0',
        ];

        $ok = @mail($to, '=?UTF-8?B?' . base64_encode($subject) . '?=', $body, implode("\r\n", $headers));
        if (! $ok) {
            return ['ok' => false, 'error' => 'Could not send your message right now. Please try again later.'];
        }

        return ['ok' => true];
    }

    /**
     * Minimal SMTP client (AUTH LOGIN) — no composer dependency.
     *
     * @return array{ok: bool, error?: string}
     */
    private function sendSmtp(
        string $host,
        string $to,
        string $fromAddress,
        string $fromName,
        string $replyEmail,
        string $replyName,
        string $subject,
        string $body
    ): array {
        $port = (int) bao_env('MAIL_PORT', 587);
        $username = (string) bao_env('MAIL_USERNAME', '');
        $password = (string) bao_env('MAIL_PASSWORD', '');
        $encryption = strtolower(trim((string) bao_env('MAIL_ENCRYPTION', 'tls')));

        $remote = ($encryption === 'ssl' ? 'ssl://' : '') . $host;
        $errno = 0;
        $errstr = '';
        $fp = @stream_socket_client(
            $remote . ':' . $port,
            $errno,
            $errstr,
            20,
            STREAM_CLIENT_CONNECT
        );
        if (! is_resource($fp)) {
            return ['ok' => false, 'error' => 'Mail server unavailable. Please try again later.'];
        }

        stream_set_timeout($fp, 20);

        try {
            $this->expect($fp, [220]);
            $this->command($fp, 'EHLO baopredictions.com', [250]);

            if ($encryption === 'tls') {
                $this->command($fp, 'STARTTLS', [220]);
                if (! stream_socket_enable_crypto($fp, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
                    throw new \RuntimeException('TLS negotiation failed');
                }
                $this->command($fp, 'EHLO baopredictions.com', [250]);
            }

            if ($username !== '') {
                $this->command($fp, 'AUTH LOGIN', [334]);
                $this->command($fp, base64_encode($username), [334]);
                $this->command($fp, base64_encode($password), [235]);
            }

            $this->command($fp, 'MAIL FROM:<' . $fromAddress . '>', [250]);
            $this->command($fp, 'RCPT TO:<' . $to . '>', [250, 251]);
            $this->command($fp, 'DATA', [354]);

            $data = 'From: ' . $this->formatAddress($fromName, $fromAddress) . "\r\n"
                . 'To: <' . $to . ">\r\n"
                . 'Reply-To: ' . $this->formatAddress($replyName, $replyEmail) . "\r\n"
                . 'Subject: =?UTF-8?B?' . base64_encode($subject) . "?=\r\n"
                . "MIME-Version: 1.0\r\n"
                . "Content-Type: text/plain; charset=UTF-8\r\n"
                . "Content-Transfer-Encoding: 8bit\r\n"
                . "\r\n"
                . str_replace(["\r\n.", "\n."], ["\r\n..", "\n.."], $body)
                . "\r\n.";

            $this->command($fp, $data, [250]);
            $this->command($fp, 'QUIT', [221, 250]);
        } catch (\Throwable $e) {
            if (function_exists('bao_log')) {
                bao_log('warning', 'Contact SMTP failed', ['error' => $e->getMessage()]);
            }
            fclose($fp);

            return ['ok' => false, 'error' => 'Could not send your message right now. Please try again later.'];
        }

        fclose($fp);

        return ['ok' => true];
    }

    /**
     * @param  resource  $fp
     * @param  list<int>  $okCodes
     */
    private function expect($fp, array $okCodes): string
    {
        $response = '';
        while (($line = fgets($fp, 515)) !== false) {
            $response .= $line;
            if (isset($line[3]) && $line[3] === ' ') {
                break;
            }
        }
        $code = (int) substr($response, 0, 3);
        if (! in_array($code, $okCodes, true)) {
            throw new \RuntimeException('Unexpected SMTP response: ' . trim($response));
        }

        return $response;
    }

    /**
     * @param  resource  $fp
     * @param  list<int>  $okCodes
     */
    private function command($fp, string $command, array $okCodes): string
    {
        fwrite($fp, $command . "\r\n");

        return $this->expect($fp, $okCodes);
    }

    private function formatAddress(string $name, string $email): string
    {
        $name = $this->oneLine($name, 80);
        $email = $this->oneLine($email, 180);
        if ($name === '') {
            return '<' . $email . '>';
        }

        return '"' . addcslashes($name, '"\\') . '" <' . $email . '>';
    }

    private function oneLine(string $value, int $max): string
    {
        $value = trim(str_replace(["\r", "\n", "\0"], '', $value));
        if (mb_strlen($value) > $max) {
            $value = mb_substr($value, 0, $max);
        }

        return $value;
    }

    private function clientIp(): string
    {
        $ip = (string) ($_SERVER['HTTP_CF_CONNECTING_IP']
            ?? $_SERVER['HTTP_X_FORWARDED_FOR']
            ?? $_SERVER['REMOTE_ADDR']
            ?? '');
        if (str_contains($ip, ',')) {
            $ip = trim(explode(',', $ip)[0]);
        }

        return $this->oneLine($ip, 64) ?: 'unknown';
    }
}

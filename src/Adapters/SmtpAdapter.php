<?php

namespace Sendportal\Base\Adapters;

use Illuminate\Support\Arr;
use Sendportal\Base\Services\Messages\MessageTrackingOptions;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mime\Address;

class SmtpAdapter extends BaseMailAdapter
{
    /** @var Mailer */
    protected $client;

    /** @var Transport */
    protected $transport;

    public function send(string $fromEmail, string $fromName, string $toEmail, string $subject, MessageTrackingOptions $trackingOptions, string $content): string
    {
        try {
            $result = $this->resolveClient()->send($this->resolveMessage($subject, $content, $fromEmail, $fromName, $toEmail));
        } catch (TransportExceptionInterface $e) {
            return $this->resolveMessageId(0);
        }

        return $this->resolveMessageId($result);
    }

    protected function resolveClient(): mixed
    {
        if (isset($this->client)) {
            return $this->client;
        }

        $this->client = new Mailer($this->resolveTransport());
        return $this->client;
    }

    protected function resolveTransport(): mixed
    {
        if (isset($this->transport)) {
            return $this->transport;
        }

        $host = Arr::get($this->config, 'host');
        $port = Arr::get($this->config, 'port');
        $username = Arr::get($this->config, 'username');
        $password = Arr::get($this->config, 'password');
        $is_gmail = Arr::get($this->config, 'is_gmail');

        $dsn = $is_gmail ? "gmail+smtp://{$username}:{$password}@default" : "smtp://{$username}:{$password}@{$host}:{$port}";

        $this->transport = Transport::fromDsn($dsn);
        return $this->transport;
    }

    protected function resolveMessage(string $subject, string $content, string $fromEmail, string $fromName, string $toEmail): mixed
    {
        return (new Email())
            ->from(new Address($fromEmail, $fromName))
            ->to($toEmail)
            ->subject($subject)
            ->html($content);
    }

    protected function resolveMessageId($result): string
    {
        return !empty($result) ? '1' : '-1';
    }
}

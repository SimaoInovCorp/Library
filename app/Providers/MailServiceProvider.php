<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;

class MailServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Disable SSL verification for SMTP (dev only)
        $this->app->afterResolving('mailer', function ($mailer) {
            $transport = $mailer->getSymfonyTransport();

            if ($transport instanceof EsmtpTransport) {
                $stream = $transport->getStream();
                $streamOptions = [
                    'ssl' => [
                        'allow_self_signed' => true,
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                    ],
                ];
                $stream->setStreamOptions($streamOptions);
            }
        });
    }
}

<?php

namespace App\Helpers;

use App\Models\EmailConfiguration;
use App\Models\Setting;
use Illuminate\Support\Facades\Mail;
use Throwable;

class MailHelper
{
    /**
     * Apply Admin → Email Configuration to Laravel mailer.
     * Call this before sending mail, or rely on AppServiceProvider boot.
     */
    public static function setMailConfig(): bool
    {
        try {
            $email_setting = EmailConfiguration::query()->first();

            if (! $email_setting || empty($email_setting->mail_host)) {
                return false;
            }

            $encryption = $email_setting->mail_encryption;
            if ($encryption === '' || $encryption === 'null' || $encryption === null) {
                $encryption = null;
            }

            $fromName = config('app.name', 'Nuter Guru');
            try {
                $setting = Setting::query()->first();
                if ($setting) {
                    $fromName = $setting->app_name
                        ?? $setting->sidebar_lg_header
                        ?? $fromName;
                }
            } catch (Throwable $e) {
                // ignore — app name fallback is fine
            }

            config([
                'mail.default' => 'smtp',
                'mail.mailers.smtp' => [
                    'transport' => 'smtp',
                    'host' => $email_setting->mail_host,
                    'port' => (int) $email_setting->mail_port,
                    'encryption' => $encryption,
                    'username' => $email_setting->smtp_username,
                    'password' => $email_setting->smtp_password,
                    'timeout' => null,
                    'auth_mode' => null,
                ],
                'mail.from.address' => $email_setting->email,
                'mail.from.name' => $fromName,
            ]);

            // Force Laravel to rebuild the SMTP transport with new config
            if (app()->bound('mail.manager')) {
                app('mail.manager')->purge('smtp');
            }

            return true;
        } catch (Throwable $e) {
            report($e);

            return false;
        }
    }

    /**
     * Send a mailable without throwing (no 500 on SMTP failure).
     */
    public static function sendTo($recipients, object $mailable): bool
    {
        if (empty($recipients)) {
            return false;
        }

        self::setMailConfig();

        try {
            Mail::to($recipients)->send($mailable);

            return true;
        } catch (Throwable $e) {
            report($e);

            return false;
        }
    }

    /**
     * Run any mail callback safely.
     */
    public static function sendSafely(callable $callback): bool
    {
        self::setMailConfig();

        try {
            $callback();

            return true;
        } catch (Throwable $e) {
            report($e);

            return false;
        }
    }

    public static function notSentMessage(): string
    {
        return trans('Email could not be sent. Everything else completed successfully.');
    }

    /**
     * Flash a soft warning when mail failed (does not mark request as error).
     */
    public static function flashNotSent(): void
    {
        session()->flash('messege', self::notSentMessage());
        session()->flash('alert-type', 'warning');
    }
}

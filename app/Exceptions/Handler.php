<?php

namespace App\Exceptions;

use App\Helpers\MailHelper;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Arr;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        // Never show 500 for mail/SMTP failures — soft warning only
        if ($this->isMailTransportFailure($e)) {
            report($e);
            $msg = MailHelper::notSentMessage();

            if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'ok' => true,
                    'success' => true,
                    'email_sent' => false,
                    'message' => $msg,
                    'notification' => $msg,
                    'messege' => $msg,
                    'alert-type' => 'warning',
                    'alert' => 'warning',
                ], 200);
            }

            return redirect()
                ->back()
                ->withInput()
                ->with('messege', $msg)
                ->with('alert-type', 'warning');
        }

        return parent::render($request, $e);
    }

    protected function isMailTransportFailure(Throwable $e): bool
    {
        if ($e instanceof TransportExceptionInterface) {
            return true;
        }

        $message = $e->getMessage();

        return str_contains($message, 'Connection could not be established with host')
            || str_contains($message, 'Unable to connect')
            || str_contains($message, 'stream_socket_client')
            || str_contains($message, 'Expected response code');
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if($request->expectsJson()){
            return response()->json(['message' => 'UnAuthenticated'], 401);
        }
        $guard=Arr::get($exception->guards(),'0');
        switch($guard){
            case 'admin':
                $login="/admin/login";
            break;

            default:
                $login="/seller/login";
        }

        return Redirect()->guest($login);
    }
}

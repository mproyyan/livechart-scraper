<?php

namespace App\Exceptions;

use App\Supports\HttpApiExceptionFormat;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Response;
use Phpro\ApiProblem\Http\UnauthorizedProblem;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Phpro\ApiProblem\Http\NotFoundProblem;
use Throwable;


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
        $this->renderable($this->handleValidationException(...));
        $this->renderable($this->handleNotFoundHttpException(...));
        $this->renderable($this->handleTooManyHttpRequestsException(...));
        $this->renderable($this->handleAuthenticationException(...));
    }

    protected function handleNotFoundHttpException(NotFoundHttpException $e, Request $request)
    {
        if ($request->is('api/*')) {
            $message = $e->getMessage();
            $notFoundProblem = new NotFoundProblem($message);

            return response($notFoundProblem->toArray(), $e->getStatusCode());
        }
    }

    protected function handleValidationException(ValidationException $e, Request $request)
    {
        if ($request->is('api/*')) {
            $errors = $e->errors();
            $error = $errors[array_key_first($errors)];

            if (count($errors) > 1 || count($error) > 1) {
                $validationProblem = new HttpApiExceptionFormat(Response::HTTP_UNPROCESSABLE_ENTITY, [
                    'detail' => "There were multiple problems on field that have occurred.",
                    'problems' => $errors
                ]);

                return response($validationProblem->toArray(), Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $validationProblem = new HttpApiExceptionFormat(Response::HTTP_UNPROCESSABLE_ENTITY, [
                'detail' => $e->getMessage(),
            ]);

            return response($validationProblem->toArray(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    protected function handleTooManyHttpRequestsException(TooManyRequestsHttpException $e, Request $request)
    {
        if ($request->is('api/*')) {
            $retryAfter = $e->getHeaders()['Retry-After'];
            $tooManyRequestsProblem = new HttpApiExceptionFormat($e->getStatusCode(), [
                'detail' => "You have exceeded the rate limit. Please try again in {$retryAfter} seconds.",
            ]);

            return response($tooManyRequestsProblem->toArray(), $e->getStatusCode());
        }
    }

    protected function handleAuthenticationException(AuthenticationException $e, Request  $request)
    {
        if ($request->is('api/*')) {
            $unauthenticatedProblem = new UnauthorizedProblem($e->getMessage());

            return response($unauthenticatedProblem->toArray(), Response::HTTP_UNAUTHORIZED);
        }
    }
}

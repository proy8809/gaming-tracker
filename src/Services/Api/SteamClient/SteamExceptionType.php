<?php

declare(strict_types=1);

namespace App\Services\Api\SteamClient;

enum SteamExceptionType: int
{
    case BadRequest = 400;
    case Unauthorized = 401;
    case Forbidden = 403;
    case NotFound = 404;
    case MethodNotAllowed = 405;
    case TooManyRequests = 429;
    case InternalServerError = 500;
    case ServiceUnavailable = 503;

    /**
     * @param int $code
     * @return string
     */
    public static function messageFor(int $code): string
    {
        return match ($code) {
            self::BadRequest->value => 'Bad Request: The request was invalid or cannot be served.',
            self::Unauthorized->value => 'Unauthorized: The request requires user authentication.',
            self::Forbidden->value => 'Forbidden: The server understood the request, but is refusing to fulfill it.',
            self::NotFound->value => 'Not Found: The requested resource could not be found.',
            self::MethodNotAllowed->value => 'Method Not Allowed: The request method is not supported for the requested resource.',
            self::TooManyRequests->value => 'Too Many Requests: You have sent too many requests in a given amount of time.',
            self::InternalServerError->value => 'Internal Server Error: An error occurred on the server.',
            self::ServiceUnavailable->value => 'Service Unavailable: The server is currently unable to handle the request due to temporary overloading or maintenance.',
            default => 'An unknown error occurred.',
        };
    }
}

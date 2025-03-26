<?php

namespace Core;

class Exceptions
{
  public const NOT_FOUND = [
    'code' => 404,
    'name' => 'NotFoundException',
    'message' => 'Resource not found'
  ];

  public const UNAUTHORIZED = [
    'code' => 401,
    'name' => 'UnauthorizedException',
    'message' => 'Unauthorized'
  ];

  public const VALIDATION = [
    'code' => 422,
    'name' => 'ValidationException',
    'message' => 'Validation error'
  ];

  public const SERVER_ERROR = [
    'code' => 500,
    'name' => 'ServerErrorException',
    'message' => 'Server error'
  ];

  public const INTERNAL_SERVER_ERROR = [
    'code' => 500,
    'name' => 'InternalServerErrorException',
    'message' => 'Internal server error'
  ];

  public const CSRF_TOKEN_MISMATCH = [
    'code' => 403,
    'name' => 'CsrfException',
    'message' => 'Invalid CSRF token'
  ];

  public const METHOD_NOT_ALLOWED = [
    'code' => 405,
    'name' => 'MethodNotAllowedException',
    'message' => 'Method not allowed'
  ];

  public const BAD_REQUEST = [
    'code' => 400,
    'name' => 'BadRequestException',
    'message' => 'Bad request'
  ];

  public const RATE_LIMIT_EXCEEDED = [
    'code' => 429,
    'name' => 'RateLimitException',
    'message' => 'Rate limit exceeded'
  ];

  public const FORBIDDEN = [
    'code' => 403,
    'name' => 'ForbiddenException',
    'message' => 'Forbidden'
  ];

  public const TOO_MANY_REQUESTS = [
    'code' => 429,
    'name' => 'TooManyRequestsException',
    'message' => 'Too many requests'
  ];


}

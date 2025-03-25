<?php

namespace Core;

class Exceptions
{
  public const NOT_FOUND = [
    'code' => 404,
    'name' => 'NotFoundException',
    'message' => 'Recurso não encontrado'
  ];

  public const UNAUTHORIZED = [
    'code' => 401,
    'name' => 'UnauthorizedException',
    'message' => 'Você precisa estar autenticado'
  ];

  public const VALIDATION = [
    'code' => 422,
    'name' => 'ValidationException',
    'message' => 'Dados inválidos'
  ];

  public const SERVER_ERROR = [
    'code' => 500,
    'name' => 'ServerErrorException',
    'message' => 'Erro interno no servidor'
  ];

  public const INTERNAL_SERVER_ERROR = [
    'code' => 500,
    'name' => 'InternalServerErrorException',
    'message' => 'Erro interno no servidor'
  ];

  public const CSRF_TOKEN_MISMATCH = [
    'code' => 403,
    'name' => 'CsrfException',
    'message' => 'CSRF token inválido ou ausente'
  ];
}

<?php

namespace App\Utils\Errors;

use App\Utils\Errors\Interfaces\IError;

class ServiceError implements IError
{

  public function __construct(private array $pilhaErro = [])
  {
  }

  public function getCodigo(): int
  {
    return 400;
  }

  public function getPilhaErro(): array
  {
    return $this->pilhaErro;
  }
}
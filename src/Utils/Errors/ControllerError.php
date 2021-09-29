<?php

namespace App\Utils\Errors;

use App\Utils\Errors\Interfaces\IError;

class ControllerError implements IError
{

  public function __construct(private array $pilhaErro = [])
  {
  }

  public function getCodigo(): int
  {
    return 422;
  }

  public function getPilhaErro(): array
  {
    return $this->pilhaErro;
  }
}
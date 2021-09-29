<?php

namespace App\Utils\Errors\Interfaces;

interface IError
{
  public function getCodigo(): int;

  public function getPilhaErro(): array;
}
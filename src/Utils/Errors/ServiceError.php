<?php

namespace App\Utils\Errors;

class ServiceError
{
  public $codigo = 400;

  public function __construct(public array $pilhaErro = [])
  {
  }

}
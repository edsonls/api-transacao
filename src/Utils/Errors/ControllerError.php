<?php

namespace App\Utils\Errors;

class ControllerError
{
  public $codigo = 422;

  public function __construct(public array $pilhaErro = [])
  {
  }

}
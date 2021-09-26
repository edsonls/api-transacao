<?php

namespace App\Services\Interfaces;


interface IUsuarioService
{
  public function add(array $usuario): int;
}
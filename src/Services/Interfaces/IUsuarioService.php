<?php

namespace App\Services\Interfaces;


use App\Entities\Usuario;

interface IUsuarioService
{
  public function add(array $usuario): int;

  public function find(int $id): Usuario;
}
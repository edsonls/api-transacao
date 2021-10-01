<?php

namespace App\Repositories\Interfaces;

use App\Entities\Usuario;

interface IUsuarioRepository
{
  function add(Usuario $usuario): int;

  public function find(int $id): ?Usuario;

  public function update(Usuario $usuario): bool;

  public function exists(string $documento, string $email): bool;
}
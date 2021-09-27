<?php

namespace App\Services\Interfaces;


use App\Entities\Usuario;

interface IUsuarioService
{
  public function add(array $usuario): int;

  public function find(int $id): Usuario;

  public function retiraSaldo(Usuario $pagador, float $valor): bool;

  public function adicionaSaldo(Usuario $pagador, float $valor): bool;
}
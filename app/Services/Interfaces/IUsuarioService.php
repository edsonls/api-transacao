<?php

namespace App\Services\Interfaces;


use App\Entities\Usuario;
use App\Utils\Errors\ServiceError;

interface IUsuarioService
{

  public function add(array $usuario): ServiceError|int;

  public function find(int $id): ServiceError|Usuario;

  public function exists(string $documento, string $email): bool;

  public function retiraSaldo(Usuario $pagador, float $valor): bool;

  public function adicionaSaldo(Usuario $recebedor, float $valor): bool;

  public function estornarSaldo(Usuario $pagador, float $valor): bool;

  public function validarSaldoPagador(Usuario $pagador, float $valor): bool;
}
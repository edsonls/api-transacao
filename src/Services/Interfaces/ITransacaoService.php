<?php

namespace App\Services\Interfaces;

use App\Entities\Usuario;
use App\Utils\Errors\ServiceError;

interface ITransacaoService
{
  const PAGADOR_SEM_SALDO = 1;
  const PAGADOR_INVALIDO = 2;
  const NAO_AUTORIZADO = 3;

  public function add(Usuario $pagador, Usuario $recebedor, float $valor): int|ServiceError;
}
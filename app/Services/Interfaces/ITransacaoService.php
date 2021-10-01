<?php

namespace App\Services\Interfaces;

use App\Entities\Usuario;
use App\Utils\Errors\ServiceError;

interface ITransacaoService
{
  public const PAGADOR_SEM_SALDO = 1;
  public const PAGADOR_INVALIDO = 2;
  public const NAO_AUTORIZADO = 3;
  public const TRANSACAO_INVALIDA = 3;

  public function add(Usuario $pagador, Usuario $recebedor, float $valor): int|ServiceError;
}
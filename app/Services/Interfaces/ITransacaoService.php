<?php

namespace App\Services\Interfaces;

use App\Entities\Usuario;
use App\Utils\Errors\ServiceError;

interface ITransacaoService
{

  public function add(Usuario $pagador, Usuario $recebedor, float $valor): int|ServiceError;
}
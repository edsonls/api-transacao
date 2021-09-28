<?php

namespace App\Services\Interfaces;

use App\Entities\Usuario;

interface INotificacaoService
{
  public function transacaoRecebida(Usuario $recebedor, float $valor): bool;
}
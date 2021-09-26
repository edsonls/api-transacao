<?php

namespace App\Entities;

use App\Entities\Enum\TipoUsuarioEnum;

class Usuario
{
  public function __construct(
    private string $nome,
    private string $cpf,
    private string $email,
    private string $senha,
    private TipoUsuarioEnum $tipoUsuario,
    private float $saldo,
  ) {
  }
}
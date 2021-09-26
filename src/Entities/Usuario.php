<?php

namespace App\Entities;

use App\Entities\Enum\TipoUsuarioEnum;

class Usuario
{
  public function __construct(
    private string $nome,
    private string $documento,
    private string $email,
    private string $senha,
    /**
     * TipoUsuarioEnum
     * @var int
     */
    private int $tipoUsuario = TipoUsuarioEnum::Comum,
    private float $saldo = 0,
  ) {
  }

  /**
   * @return string
   */
  public function getNome(): string
  {
    return $this->nome;
  }

  /**
   * @return string
   */
  public function getDocumento(): string
  {
    return $this->documento;
  }

  /**
   * @return string
   */
  public function getEmail(): string
  {
    return $this->email;
  }

  /**
   * @return string
   */
  public function getSenha(): string
  {
    return $this->senha;
  }

  /**
   * @return int
   */
  public function getTipoUsuario(): int
  {
    return $this->tipoUsuario;
  }

  /**
   * @return float
   */
  public function getSaldo(): float
  {
    return $this->saldo;
  }


}
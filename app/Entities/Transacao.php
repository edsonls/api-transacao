<?php

namespace App\Entities;


class Transacao
{
  public function __construct(
    private Usuario $pagador,
    private Usuario $recebedor,
    private float $valor,
  ) {
  }

  /**
   * @return float
   */
  public function getValor(): float
  {
    return $this->valor;
  }

  /**
   * @return Usuario
   */
  public function getRecebedor(): Usuario
  {
    return $this->recebedor;
  }

  /**
   * @return Usuario
   */
  public function getPagador(): Usuario
  {
    return $this->pagador;
  }
}
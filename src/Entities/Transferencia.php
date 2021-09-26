<?php

namespace App\Entities;


class Transferencia
{
  public function __construct(
    private Usuario $pagador,
    private Usuario $recebedor,
    private float $valor,
  ) {
  }
}
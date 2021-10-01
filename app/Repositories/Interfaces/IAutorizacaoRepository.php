<?php

namespace App\Repositories\Interfaces;

interface IAutorizacaoRepository
{
  public function autoriza(): bool;
}
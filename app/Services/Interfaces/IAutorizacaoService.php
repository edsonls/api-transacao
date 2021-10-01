<?php

namespace App\Services\Interfaces;

interface IAutorizacaoService
{
  public function autoriza(): bool;
}
<?php

namespace App\Repositories\Interfaces;

interface INotificacaoRepository
{
  public function send(): bool;
}
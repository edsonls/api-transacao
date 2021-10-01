<?php

namespace App\Repositories\Interfaces;

interface INotificacaoRepository
{
  public function send(string $nome, string $email, float $valor): bool;
}
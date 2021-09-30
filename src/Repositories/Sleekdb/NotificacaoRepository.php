<?php

namespace App\Repositories\Sleekdb;

use App\Providers\DataBase\SleekDB;
use App\Repositories\Interfaces\INotificacaoRepository;
use App\Utils\Log\AppLog;
use Exception;

class NotificacaoRepository extends SleekDB implements INotificacaoRepository
{

  public function __construct()
  {
    $this->table = 'notificacoes';
  }

  public function send(string $nome, string $email, float $valor): bool
  {
    try {
      return !empty(
      $this->getConnection()->insert(
        ['nome' => $nome, 'email' => $email, 'valor' => $valor, 'enviado' => false]
      )
      );
    } catch (Exception $exception) {
      AppLog::error('NotificacaoRepository', $exception->getMessage());
      return false;
    }
  }
}
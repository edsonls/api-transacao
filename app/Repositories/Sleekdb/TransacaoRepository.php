<?php

namespace App\Repositories\Sleekdb;

use App\Entities\Transacao;
use App\Providers\DataBase\SleekDB;
use App\Repositories\Interfaces\ITransacaoRepository;
use App\Utils\Log\AppLog;
use Exception;

class TransacaoRepository extends SleekDB implements ITransacaoRepository
{

  public function __construct()
  {
    $this->table = 'transacao';
  }


  public function add(Transacao $transacao): ?int
  {
    try {
      return $this->getConnection()->insert(
        [
          'pagador' => $transacao->getPagador()->getDocumento(),
          'recebedor' => $transacao->getRecebedor()->getDocumento(),
          'valor' => $transacao->getValor(),
        ]
      )
      ['_id'];
    } catch (Exception $exception) {
      AppLog::error('TransacaoRepository', $exception->getMessage());
      return null;
    }
  }

  public function delete(int $idTransacao): bool
  {
    try {
      return $this->getConnection()->deleteById($idTransacao);
    } catch (Exception $exception) {
      AppLog::error('TransacaoRepository', $exception->getMessage());
      return false;
    }
  }
}
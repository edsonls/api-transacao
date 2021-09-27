<?php

namespace App\Repositories\Sleekdb;

use App\Entities\Transacao;
use App\Providers\DataBase\SleekDB;
use App\Repositories\Interfaces\ITransacaoRepository;
use SleekDB\Exceptions\IdNotAllowedException;
use SleekDB\Exceptions\InvalidArgumentException;
use SleekDB\Exceptions\InvalidConfigurationException;
use SleekDB\Exceptions\IOException;
use SleekDB\Exceptions\JsonException;

class TransacaoRepository extends SleekDB implements ITransacaoRepository
{

  public function __construct()
  {
    $this->table = 'transacao';
  }

  /**
   * @throws IOException
   * @throws JsonException
   * @throws InvalidArgumentException
   * @throws IdNotAllowedException
   * @throws InvalidConfigurationException
   */
  public function add(Transacao $transacao): int
  {
    return $this->getConnection()->insert(
      [
        'pagador' => $transacao->getPagador()->getDocumento(),
        'recebedor' => $transacao->getRecebedor()->getDocumento(),
        'valor' => $transacao->getValor(),
      ]
    )
    ['_id'];
  }

  public function delete(int $idTransacao): bool
  {
    return $this->getConnection()->deleteById($idTransacao);
  }
}
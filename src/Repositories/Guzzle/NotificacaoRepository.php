<?php

namespace App\Repositories\Guzzle;

use App\Providers\HttpClient\Guzzle;
use App\Repositories\Interfaces\INotificacaoRepository;
use App\Utils\Log\AppLog;
use Exception;
use GuzzleHttp\Exception\GuzzleException;

class NotificacaoRepository extends Guzzle implements INotificacaoRepository
{
  const URL = 'http://o4d9z.mocklab.io/notify';

  public function send(): bool
  {
    try {
      $response = $this->getClient()->get(self::URL);
      AppLog::info('NotificacaoRepository', 'Notificacao Enviada status: ' . $response->getStatusCode());
      return true;
    } catch (Exception | GuzzleException $exception) {
      AppLog::error('NotificacaoRepository', $exception->getMessage());
      return false;
    }
  }
}
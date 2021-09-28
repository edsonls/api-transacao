<?php

namespace App\Repositories\Guzzle;

use App\Providers\HttpClient\Guzzle;
use App\Repositories\Interfaces\INotificacaoRepository;
use App\Utils\Log\AppLog;
use Exception;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;

class NotificacaoRepository extends Guzzle implements INotificacaoRepository
{
  const URL = 'http://o4d9z.mocklab.io/notify';

  public function send(): bool
  {
    try {
      $promise = $this->getClient()->requestAsync('GET', self::URL);
      $promise->then(
        function (ResponseInterface $res) {
          AppLog::info('NotificacaoRepository', 'Notificacao Enviada status: ' . $res->getStatusCode());
        },
        function (RequestException $e) {
          AppLog::error('NotificacaoRepository', 'msg: ' . $e->getMessage());
        }
      );
      return true;
    } catch (Exception $exception) {
      AppLog::error('NotificacaoRepository', $exception->getMessage());
      return false;
    }
  }
}
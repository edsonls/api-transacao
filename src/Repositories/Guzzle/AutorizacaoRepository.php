<?php

namespace App\Repositories\Guzzle;

use App\Providers\HttpClient\Guzzle;
use App\Repositories\Interfaces\IAutorizacaoRepository;
use App\Utils\Log\AppLog;
use Exception;
use GuzzleHttp\Exception\GuzzleException;

class AutorizacaoRepository extends Guzzle implements IAutorizacaoRepository
{
  const URL = 'https://run.mocky.io/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6';
  const AUTORIZADO = 'Autorizado';

  public function autoriza(): bool
  {
    try {
      $response = $this->getClient()->get(self::URL);
      $body = json_decode((string)$response->getBody(), false, 512, JSON_THROW_ON_ERROR);
      return $response->getStatusCode() === 200 && $body?->message === self::AUTORIZADO;
    } catch (GuzzleException | Exception $exception) {
      AppLog::error('AutorizacaoRepository', $exception->getMessage());
      return false;
    }
  }
}
<?php

require __DIR__ . '/../vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use SleekDB\Store;

$log = new Logger('Fila');
$podeProcessar = true;

while (true) {
  try {
    $a = getNotificacoes();
    if (!empty($a)) {
      sendNotificacoes($a);
    }
    sleep(5);
  } catch (Exception $exception) {
    $log->pushHandler(new StreamHandler('fila.log'));
    $log->error($exception->getMessage());
  }
}

function getDb(): Store
{
  return new Store('notificacoes', '/app/dataBase', [
    "auto_cache" => true,
    "cache_lifetime" => null,
    "timeout" => false, // deprecated! Set it to false! 10*20=100
    "primary_key" => "_id",
  ]);
}

function getNotificacoes(): ?array
{
  return getDb()->findBy([['enviado', '=', false]], limit: 10);
}

function sendNotificacoes(array $notificacoes)
{
  $client = new Client(['timeout' => 20]);

  $requests = function ($total) {
    $uri = 'http://o4d9z.mocklab.io/notify';
    for ($i = 0; $i < $total; $i++) {
      yield new Request('GET', $uri);
    }
  };

  $pool = new Pool($client, $requests(count($notificacoes)), [
    'concurrency' => 10,
    'fulfilled' => function (Response $response, $index) use ($notificacoes) {
      echo $notificacoes[$index]['_id'] . ' MSG Enviada' . PHP_EOL;
      updateNotificacao($notificacoes[$index]);
    },
    'rejected' => function (RequestException $reason, $index) {
      echo $index . 'MSG ñ Enviada' . PHP_EOL;
    },
  ]);

// Initiate the transfers and create a promise
  $promise = $pool->promise();
// Force the pool of requests to complete.
  $promise->wait();
}

function updateNotificacao(array $notificacao)
{
  getDb()->updateById($notificacao['_id'], ['enviado' => true]);
}
<?php

require __DIR__ . '/../vendor/autoload.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


use App\Controllers\TransacaoController;
use App\Controllers\UsuarioController;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;

$app = AppFactory::create();

$app->addRoutingMiddleware();

// Monolog Example
$logger = new Logger('app');
$streamHandler = new StreamHandler(__DIR__ . '/logs/rest.log', 100);
$logger->pushHandler($streamHandler);
// Add Error Middleware with Logger
$errorMiddleware = $app->addErrorMiddleware(false, true, true, $logger);

$app->group('/v1', function (RouteCollectorProxy $group) {
  $group->post(
    '/usuarios',
    function (Request $request, Response $response) {
      $resp = $response
        ->withHeader('Content-Type', 'application/json');
      $usuarioController = new UsuarioController();
      $resposta = $usuarioController->add($request->getBody());
      if (is_int($resposta)) {
        $resp->getBody()->write(json_encode(['id' => $resposta]));
        return $resp->withStatus(201);
      }
      $resp->getBody()->write(json_encode($resposta->getPilhaErro()));
      return $resp->withStatus($resposta->getCodigo());
    }
  );

  $group->post(
    '/transacoes',
    function (Request $request, Response $response) {
      $resp = $response
        ->withHeader('Content-Type', 'application/json');
      $transacaoController = new TransacaoController();
      $resposta = $transacaoController->add($request->getBody());
      if (is_int($resposta)) {
        $resp->getBody()->write(json_encode(['id' => $resposta]));
        return $resp->withStatus(201);
      }
      $resp->getBody()->write(json_encode($resposta->getPilhaErro()));
      return $resp->withStatus($resposta->getCodigo());
    }
  );
});


$app->run();
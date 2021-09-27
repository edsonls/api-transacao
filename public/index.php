<?php

require __DIR__ . '/../vendor/autoload.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


use App\Controllers\TransacaoController;
use App\Controllers\UsuarioController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

$app = AppFactory::create();
//Usuario
$app->post(
  '/usuario',
  function (Request $request, Response $response) {
    $resp = $response
      ->withHeader('Content-Type', 'application/json');
    $usuarioController = new UsuarioController();
    $resp->getBody()->write(json_encode(['id' => $usuarioController->add($request->getBody())]));
    return $resp->withStatus(201);
  }
);
$app->post(
  '/transacao',
  function (Request $request, Response $response) {
    $resp = $response
      ->withHeader('Content-Type', 'application/json');
    $transacaoController = new TransacaoController();
    $resp->getBody()->write(json_encode(['id' => $transacaoController->add($request->getBody())]));
    return $resp->withStatus(201);
  }
);

$app->run();
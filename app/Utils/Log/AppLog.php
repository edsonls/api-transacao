<?php

namespace App\Utils\Log;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

abstract class AppLog
{
  private const CAMINHO = 'logs/';

  public static function warning(string $onde = 'service', string $message = ''): void
  {
    self::getLogger($onde)->warning($message);
  }

  public static function error(string $onde = 'service', string $message = ''): void
  {
    self::getLogger($onde)->error($message);
  }

  public static function info(string $onde = 'service', string $message = ''): void
  {
    self::getLogger($onde)->info($message);
  }

  private static function getLogger($onde)
  {
    $log = new Logger($onde);
    $log->pushHandler(new StreamHandler(self::CAMINHO . $onde . '.log'));
    return $log;
  }
}
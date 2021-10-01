<?php

namespace App\Utils\Log;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

abstract class AppLog
{
  private const CAMINHO = 'logs/';

  public static function warning(string $onde = 'service', string $message = ''): void
  {
    $log = new Logger($onde);
    $log->pushHandler(new StreamHandler(self::CAMINHO . $onde . '.log'));
    $log->warning($message);
  }

  public static function error(string $onde = 'service', string $message = ''): void
  {
    $log = new Logger($onde);
    $log->pushHandler(new StreamHandler(self::CAMINHO . $onde . '.log'));
    $log->error($message);
  }

  public static function info(string $onde = 'service', string $message = ''): void
  {
    $log = new Logger($onde);
    $log->pushHandler(new StreamHandler(self::CAMINHO . $onde . '.log'));
    $log->info($message);
  }
}
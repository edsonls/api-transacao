<?php


use App\Utils\Log\AppLog;

it(
  'OK - Gera Arquivo log',
  function () {
    AppLog::info('log', 'isso é um log de teste');
    expect(
      file_exists('logs' . DIRECTORY_SEPARATOR . 'log.log')
    )
      ->toBeTrue();
    unlink('logs' . DIRECTORY_SEPARATOR . 'log.log');
  }
);

it(
  'OK - Gera Log info e checa conteudo',
  function () {
    AppLog::info('info', 'isso é um log de teste');
    $this->assertStringContainsString(
      'info.INFO',
      fgets(fopen('logs' . DIRECTORY_SEPARATOR . 'info.log', 'r'))
    );
    unlink('logs' . DIRECTORY_SEPARATOR . 'info.log');
  }
);

it(
  'OK - Gera Log warning e checa conteudo',
  function () {
    AppLog::warning('warning', 'isso é um log de teste');
    $this->assertStringContainsString(
      'warning.WARNING',
      fgets(fopen('logs' . DIRECTORY_SEPARATOR . 'warning.log', 'r'))
    );
    unlink('logs' . DIRECTORY_SEPARATOR . 'warning.log');
  }
);

it(
  'OK - Gera Log error e checa conteudo',
  function () {
    AppLog::error('error', 'isso é um log de teste');
    $this->assertStringContainsString(
      'error.ERROR',
      fgets(fopen('logs' . DIRECTORY_SEPARATOR . 'error.log', 'r'))
    );
    unlink('logs' . DIRECTORY_SEPARATOR . 'error.log');
  }
);
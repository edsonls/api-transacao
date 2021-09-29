<?php


use App\Controllers\TransacaoController;
use App\Entities\Enum\TipoUsuarioEnum;
use App\Repositories\Sleekdb\UsuarioRepository;
use App\Services\UsuarioService;
use App\Utils\Errors\Interfaces\IError;
use GuzzleHttp\Psr7\Utils;

beforeEach(
  function () {
    $this->fake = Faker\Factory::create('pt_BR');
    $this->transacaoController = new TransacaoController();
    $this->usuarioService = new UsuarioService(new UsuarioRepository());
    $this->pagadorId = $this->usuarioService->add(
      [
        'nome' => $this->fake->name,
        'documento' => $this->fake->cpf,
        'email' => $this->fake->email,
        'senha' => $this->fake->password,
        'tipoUsuario' => TipoUsuarioEnum::Comum,
        'saldo' => $this->fake->randomFloat(2, 100, 100),
      ]
    );
    $this->recebedorId = $this->usuarioService->add(
      [
        'nome' => $this->fake->name,
        'documento' => $this->fake->cpf,
        'email' => $this->fake->email,
        'senha' => $this->fake->password,
        'tipoUsuario' => TipoUsuarioEnum::Comum,
        'saldo' => $this->fake->randomFloat(2, 100, 100),
      ]
    );
    $this->lojistaId = $this->usuarioService->add(
      [
        'nome' => $this->fake->name,
        'documento' => $this->fake->cpf,
        'email' => $this->fake->email,
        'senha' => $this->fake->password,
        'tipoUsuario' => TipoUsuarioEnum::Logista,
        'saldo' => $this->fake->randomFloat(2, 100, 100),
      ]
    );
  }
);
it(
  'OK - Transacao Usuario -> Usuario',
  function () {
    expect(
      $this->transacaoController->add(
        Utils::streamFor(
          json_encode([
                        'pagador' => $this->pagadorId,
                        'recebedor' => $this->recebedorId,
                        'valor' => 50.85,
                      ])
        )
      )
    )
      ->toBeInt();
  }
);
it(
  'OK - Transacao Usuario -> Lojista',
  function () {
    expect(
      $this->transacaoController->add(
        Utils::streamFor(
          json_encode([
                        'pagador' => $this->pagadorId,
                        'recebedor' => $this->lojistaId,
                        'valor' => 50.85,
                      ])
        )
      )
    )
      ->toBeInt();
  }
);

it(
  'Fail - Transacao Lojista -> Usuario',
  function () {
    expect(
      $this->transacaoController->add(
        Utils::streamFor(
          json_encode([
                        'pagador' => $this->lojistaId,
                        'recebedor' => $this->pagadorId,
                        'valor' => 50.85,
                      ])
        )
      )
    )
      ->toBeInstanceOf(IError::class);
  }
);
it(
  'Fail - Transacao Lojista -> Lojista',
  function () {
    expect(
      $this->transacaoController->add(
        Utils::streamFor(
          json_encode([
                        'pagador' => $this->lojistaId,
                        'recebedor' => $this->lojistaId,
                        'valor' => 50.85,
                      ])
        )
      )
    )
      ->toBeInstanceOf(IError::class);
  }
);
it(
  'Fail - Transacao Usuario -> Lojista Valor negativo',
  function () {
    expect(
      $this->transacaoController->add(
        Utils::streamFor(
          json_encode([
                        'pagador' => $this->pagadorId,
                        'recebedor' => $this->lojistaId,
                        'valor' => -50.85,
                      ])
        )
      )
    )
      ->toBeInstanceOf(IError::class);
  }
);
it(
  'Fail - Transacao Usuario invalido -> Lojista',
  function () {
    expect(
      $this->transacaoController->add(
        Utils::streamFor(
          json_encode([
                        'pagador' => 'abc',
                        'recebedor' => $this->lojistaId,
                        'valor' => 50.85,
                      ])
        )
      )
    )
      ->toBeInstanceOf(IError::class);
  }
);
it(
  'Fail - Transacao Usuario -> Lojista invalido',
  function () {
    expect(
      $this->transacaoController->add(
        Utils::streamFor(
          json_encode([
                        'pagador' => $this->recebedorId,
                        'recebedor' => 'asdasd',
                        'valor' => 50.85,
                      ])
        )
      )
    )
      ->toBeInstanceOf(IError::class);
  }
);
it(
  'Fail - Transacao Usuario invalido -> Lojista invalido',
  function () {
    expect(
      $this->transacaoController->add(
        Utils::streamFor(
          json_encode([
                        'pagador' => 'sadasdasdas',
                        'recebedor' => 'asdasd',
                        'valor' => 50.85,
                      ])
        )
      )
    )
      ->toBeInstanceOf(IError::class);
  }
);
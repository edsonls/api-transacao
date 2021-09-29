<?php


use App\Controllers\UsuarioController;
use App\Entities\Enum\TipoUsuarioEnum;
use GuzzleHttp\Psr7\Utils;

beforeEach(
  function () {
    $this->fake = Faker\Factory::create('pt_BR');
    $this->usuarioController = new UsuarioController();
  }
);
it(
  'OK - Criando Usuario Comum',
  function () {
    expect(
      $this->usuarioController->add(
        Utils::streamFor(
          json_encode([
                        'nome' => $this->fake->name,
                        'documento' => $this->fake->cpf(false),
                        'email' => $this->fake->email,
                        'senha' => $this->fake->password,
                        'tipoUsuario' => TipoUsuarioEnum::Comum,
                        'saldo' => 50,
                      ])
        )
      )
    )
      ->toBeInt();
  }
);
it(
  'OK - Criando Usuario Logista',
  function () {
    expect(
      $this->usuarioController->add(
        Utils::streamFor(
          json_encode([
                        'nome' => $this->fake->name,
                        'documento' => $this->fake->cpf(false),
                        'email' => $this->fake->email,
                        'senha' => $this->fake->password,
                        'tipoUsuario' => TipoUsuarioEnum::Logista,
                        'saldo' => 50,
                      ])
        )
      )
    )
      ->toBeInt();
  }
);
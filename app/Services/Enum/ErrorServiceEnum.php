<?php

namespace App\Services\Enum;

abstract class ErrorServiceEnum
{

  public const UsuarioNaoEncontrado = 1;
  public const UsuarioJaCadastrado = 2;
  public const PagadorSemSaldo = 3;
  public const PagadorInvalido = 4;
  public const NaoAutorizado = 5;
  public const TransacaoInvalida = 6;

}
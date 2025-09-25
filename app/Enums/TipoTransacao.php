<?php

namespace App\Enums;

enum TipoTransacao: string
{
    case Entrada = 'entrada';
    case Saida = 'saida';
    case Transferencia = 'transferencia';
}

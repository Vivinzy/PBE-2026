<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;

abstract class Controller
{
    Log::info('Notificação enviada para responsável: saída do aluno autorizada.');
}

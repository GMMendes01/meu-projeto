<?php

use App\Models\Produto;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // Busca no banco usando o Eloquent ORM
    $destaques = Produto::where('destaque', 1)->get();
    $produtosGerais = Produto::where('destaque', 0)->get();

    return view('teste', compact('destaques', 'produtosGerais'));
});
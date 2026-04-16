<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CarrinhoController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\UserController;
use App\Models\Produto;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    $destaques = Produto::where('destaque', 1)->get();
    $produtosGerais = Produto::all();
    $categorias = Produto::distinct()->pluck('categoria');

    return view('index', compact('destaques', 'produtosGerais', 'categorias'));
});

Route::get('/carrinho', [CarrinhoController::class, 'index'])->name('carrinho.index');
Route::get('/api/carrinho', [CarrinhoController::class, 'getCarrinho'])->name('carrinho.get');
Route::post('/carrinho/{produto}', [CarrinhoController::class, 'store'])->name('carrinho.add');
Route::put('/carrinho/{produto}', [CarrinhoController::class, 'update'])->name('carrinho.update');
Route::delete('/carrinho/{produto}', [CarrinhoController::class, 'destroy'])->name('carrinho.remove');
Route::delete('/carrinho', [CarrinhoController::class, 'clear'])->name('carrinho.clear');

//Rotas de Conta
Route::get('/meusdados',[UserController::class, 'meusdados']);
Route::post('/meusdados',[UserController::class, 'meusdados']);

// Rotas de Registro
Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store']);
Route::get('/empresa/{cnpj}', [EmpresaController::class, 'consultarCnpj']);
// Rotas de Login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
});
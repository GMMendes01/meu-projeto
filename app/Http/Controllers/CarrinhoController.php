<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use Illuminate\Http\Request;

class CarrinhoController extends Controller
{
    public function getCarrinho()
    {
        $itensCarrinho = $this->montarItens();
        $total = collect($itensCarrinho)->sum('subtotal');
        $quantidadeTotal = collect($itensCarrinho)->sum('quantidade');

        return response()->json([
            'carrinho' => $itensCarrinho,
            'total' => $total,
            'quantidadeTotal' => $quantidadeTotal,
        ]);
    }

    public function index()
    {
        $itens = $this->montarItens();
        $total = collect($itens)->sum('subtotal');
        $quantidadeTotal = collect($itens)->sum('quantidade');

        return view('carrinho', compact('itens', 'total', 'quantidadeTotal'));
    }

    public function store(Request $request, Produto $produto)
    {
        $validated = $request->validate([
            'quantidade' => ['required', 'integer', 'min:1'],
        ]);

        if ((int) $produto->quantidade <= 0) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Este produto está esgotado.',
                ], 400);
            }
            return back()->with('error', 'Este produto está esgotado.');
        }

        $carrinho = session()->get('carrinho', []);
        $quantidadeAtual = (int) ($carrinho[$produto->id] ?? 0);
        $novaQuantidade = min($quantidadeAtual + (int) $validated['quantidade'], (int) $produto->quantidade);

        $carrinho[$produto->id] = $novaQuantidade;
        session()->put('carrinho', $carrinho);

        if ($request->wantsJson()) {
            $itensCarrinho = $this->montarItens();
            $total = collect($itensCarrinho)->sum('subtotal');
            $quantidadeTotal = collect($itensCarrinho)->sum('quantidade');
            
            return response()->json([
                'success' => true,
                'message' => "{$produto->nome} foi adicionado ao carrinho.",
                'carrinho' => $itensCarrinho,
                'total' => $total,
                'quantidadeTotal' => $quantidadeTotal,
            ]);
        }

        return back()->with('success', "{$produto->nome} foi adicionado ao carrinho.");
    }

    public function update(Request $request, Produto $produto)
    {
        $validated = $request->validate([
            'quantidade' => ['required', 'integer', 'min:1'],
        ]);

        if ((int) $produto->quantidade <= 0) {
            return $this->destroy($request, $produto);
        }

        $carrinho = session()->get('carrinho', []);
        $carrinho[$produto->id] = min((int) $validated['quantidade'], (int) $produto->quantidade);
        session()->put('carrinho', $carrinho);

        if ($request->wantsJson()) {
            $itensCarrinho = $this->montarItens();
            $total = collect($itensCarrinho)->sum('subtotal');
            $quantidadeTotal = collect($itensCarrinho)->sum('quantidade');
            
            return response()->json([
                'success' => true,
                'message' => 'Quantidade atualizada.',
                'carrinho' => $itensCarrinho,
                'total' => $total,
                'quantidadeTotal' => $quantidadeTotal,
            ]);
        }

        return back()->with('success', 'Quantidade atualizada.');
    }

    public function destroy(Request $request, Produto $produto)
    {
        $carrinho = session()->get('carrinho', []);
        unset($carrinho[$produto->id]);
        session()->put('carrinho', $carrinho);

        if ($request->wantsJson()) {
            $itensCarrinho = $this->montarItens();
            $total = collect($itensCarrinho)->sum('subtotal');
            $quantidadeTotal = collect($itensCarrinho)->sum('quantidade');
            
            return response()->json([
                'success' => true,
                'message' => 'Produto removido do carrinho.',
                'carrinho' => $itensCarrinho,
                'total' => $total,
                'quantidadeTotal' => $quantidadeTotal,
            ]);
        }

        return back()->with('success', 'Produto removido do carrinho.');
    }

    public function clear(Request $request)
    {
        session()->forget('carrinho');

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Carrinho limpo.',
                'carrinho' => [],
                'total' => 0,
                'quantidadeTotal' => 0,
            ]);
        }

        return back()->with('success', 'Carrinho limpo.');
    }

    private function montarItens(): array
    {
        $carrinho = session()->get('carrinho', []);
        $produtos = Produto::whereIn('id', array_keys($carrinho))->get()->keyBy('id');

        $itens = [];
        $carrinhoLimpo = [];

        foreach ($carrinho as $produtoId => $quantidadeSolicitada) {
            $produto = $produtos->get($produtoId);

            if (! $produto) {
                continue;
            }

            $estoqueDisponivel = (int) $produto->quantidade;

            if ($estoqueDisponivel <= 0) {
                continue;
            }

            $quantidade = min((int) $quantidadeSolicitada, $estoqueDisponivel);

            if ($quantidade <= 0) {
                continue;
            }

            $subtotal = $quantidade * (float) $produto->preco_atual;

            $itens[] = [
                'produto' => $produto,
                'quantidade' => $quantidade,
                'subtotal' => $subtotal,
            ];

            $carrinhoLimpo[$produto->id] = $quantidade;
        }

        session()->put('carrinho', $carrinhoLimpo);

        return $itens;
    }
}
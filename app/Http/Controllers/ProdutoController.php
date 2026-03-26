public function index()
{
    $destaques = Produto::where('destaque', true)->get();
    $produtosGerais = Produto::where('destaque', false)->get();

    return view('home', compact('destaques', 'produtosGerais'));
}
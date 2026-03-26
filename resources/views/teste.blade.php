<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Distribuidora Foccus | Portal B2B</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    
    <style>
        .swiper { width: 100%; padding-bottom: 40px; }
        .swiper-button-next, .swiper-button-prev { color: #1e293b; }
    </style>
</head>
<body class="bg-gray-50 font-sans">

    <nav class="bg-slate-500 text-white p-4 shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <img src="{{ asset('LOGO_FOCCUS.png') }}" width="200" alt="Logo Foccus">
            <div class="space-x-6 hidden md:flex text-sm font-medium">
                <a href="#" class="hover:text-slate-200 transition">Catálogo</a>
                <a href="#" class="hover:text-slate-200 transition">Meus Pedidos</a>
                <a href="#" class="hover:text-slate-200 transition">Financeiro</a>
                <a href="#" class="hover:text-slate-200 transition">Contato</a>
            </div>
            <div class="bg-slate-800 px-4 py-2 rounded-full cursor-pointer hover:bg-slate-700 transition">
                <span class="text-xs">🛒 Carrinho</span>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto p-6 md:p-10">
        
        <section class="mb-16">
            <div class="flex items-center gap-2 mb-6">
                <span class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">HOT</span>
                <h2 class="text-2xl font-bold text-gray-800">Ofertas da Semana</h2>
            </div>

            <div class="swiper mySwiper">
                <div class="swiper-wrapper">
                    @foreach($destaques as $item)
                        <div class="swiper-slide bg-white rounded-xl shadow-md border border-red-50 border-opacity-50 overflow-hidden">
                            <div class="relative">
                                <img src="{{ $item->imagem ?? 'https://via.placeholder.com/300x200' }}" class="w-full h-48 object-cover">
                                @if($item->preco_antigo)
                                    <span class="absolute top-2 right-2 bg-red-600 text-white text-xs font-bold px-2 py-1 rounded">
                                        OFF
                                    </span>
                                @endif
                            </div>
                            <div class="p-4">
                                <h3 class="font-bold text-gray-700 truncate">{{ $item->nome }}</h3>
                                <div class="mt-2">
                                    @if($item->preco_antigo)
                                        <span class="text-xs text-gray-400 line-through">R$ {{ number_format($item->preco_antigo, 2, ',', '.') }}</span>
                                    @endif
                                    <p class="text-xl font-black text-red-600">R$ {{ number_format($item->preco_atual, 2, ',', '.') }}</p>
                                </div>
                                <button class="w-full mt-3 bg-slate-800 text-white py-2 rounded-lg text-sm font-bold hover:bg-slate-900 transition">
                                    🛒 Adicionar
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="swiper-pagination"></div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        </section>

        <hr class="mb-16 border-gray-200">

        <header class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">Catálogo Geral</h2>
                <p class="text-gray-500">Explore todos os nossos produtos em estoque.</p>
            </div>
            <div class="w-full md:w-80">
                <input type="text" placeholder="Buscar produto..." class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
        </header>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ($produtosGerais as $produto)
                <div class="border p-4 rounded-lg shadow-sm bg-white hover:shadow-md transition-all group">
                    <div class="h-40 bg-gray-100 mb-4 rounded flex items-center justify-center overflow-hidden">
                         <img src="{{ $produto->imagem ?? 'https://via.placeholder.com/150' }}" class="group-hover:scale-110 transition-transform duration-300">
                    </div>
                    <h3 class="font-bold text-lg text-gray-800">{{ $produto->nome }}</h3>
                    <p class="text-sm text-gray-500">Estoque: {{ $produto->estoque }} un.</p>
                    <p class="text-blue-700 font-bold text-xl mt-2 font-mono">
                        R$ {{ number_format($produto->preco_atual, 2, ',', '.') }}
                    </p>
                    <button class="w-full mt-4 bg-blue-600 text-white py-2 rounded-md font-medium hover:bg-blue-700 transition-colors">
                        Adicionar ao Pedido
                    </button>
                </div>
            @endforeach
        </div>
    </main>

    <footer class="mt-20 border-t border-gray-200 bg-white p-8 text-center text-gray-400 text-sm">
        &copy; {{ date('Y') }} Distribuidora Foccus - Todos os direitos reservados.
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        var swiper = new Swiper(".mySwiper", {
            slidesPerView: 1,
            spaceBetween: 25,
            loop: true,
            autoplay: { delay: 4000 },
            pagination: { el: ".swiper-pagination", clickable: true },
            navigation: { nextEl: ".swiper-button-next", prevEl: ".swiper-button-prev" },
            breakpoints: {
                640: { slidesPerView: 2 },
                1024: { slidesPerView: 4 },
            },
        });
    </script>
</body>
</html>
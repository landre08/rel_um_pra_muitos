<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Produto;
use App\Categoria;

Route::get('/categorias', function () {
    $cats = Categoria::all();
    if (count($cats) === 0) {
        echo "<h4>Você não possui nenhuma categoria cadastrados</h4>";
    } else {
        foreach($cats as $cat) {
            echo "<p>".$cat->id." ".$cat->nome."</p>";
        }
    }
});

Route::get('/produtos', function () {
    $prods = Produto::all();
    if (count($prods) === 0) {
        echo "<h4>Você não possui nenhum produto cadastrados</h4>";
    } else {
        foreach($prods as $prod) {
            echo "<p>".$prod->id." - ".$prod->nome."</p>";
            echo "<p>Categoria: ".$prod->nome."</p>";
        }
    }
});

Route::get('/categoriasprodutos', function () {
    $cats = Categoria::all();
    if (count($cats) === 0) {
        echo "<h4>Você não possui nenhuma categoria cadastrada</h4>";
    } else {
        foreach($cats as $cat) {
            echo "<p>".$cat->id." - ".$cat->nome."</p>";
            $produtos = $c->produtos;

            if (count($produtos) > 0) {
                echo "<ul>";
                foreach($produtos as $prod) {
                    echo "<li>".$prod->nome."</li>";
                }
                echo "</ul>";
            }
        }
    }
});

Route::get('/categoriasprodutos/json', function () {
    // Carregamento preguiçoso, por padrão não vem os produtos
    // $cats = Categoria::all();

    // Agora vem os produtos.
    $cats = Categoria::with('produtos')->get();

    return $cats;
});

// To em produto e quero associar a uma categoria.
Route::get('/adicionarprodutos', function () {
    // 1) Quando vem do formulário um campo oculto categoria_id. Só usar $p->categoria = $request['i'];
    // 2) Quando tenho tenho a instância da categoria, assim: $cat = Categoria::find(1);, basta fazer: $p->categoria()->associate($cat); Isso irá setar o categoria_id em produto com o id da categoria.
    $cat = Categoria::find(1);
    $p = new Produto();
    $p->nome = "Novo Produto";
    $p->estoque = 10;
    $p->preco = 100;
    $p->categoria()->associate($cat);
    $p->save();

    // Retorna o produto e a categoria pertencente a ele no json
    return $p->toJson();
});

// To em produto e quero dissociar de uma categoria.
Route::get('/removerprodutocategoria', function () {
    $p = Produto::find(5);
    if ($p) {
        // Produto vai continuar existindo na base, porém o campo categoria_id vai ficar null.
        $p->categoria()->dissociate();
        $p->save();

        // Retorna o produto, porem a categoria null.
        return $p->toJson();
    }

    return '';
});

// Agora tô em categoria e quero a partir dele adicionar produtos.
Route::get('adicionarproduto/{catid}', function ($catid) {
    // Assim vem a categoria, mas sem os produtos dela
    // $cat = Categoria::find($catid);

    // Assim vem a categoria, junto com os produtos dela
    $cat = Categoria::with('produtos')->find($catid);

    $p = new Produto();
    $p->nome = "Novo Produto Adicionado 2";
    $p->estoque = 44;
    $p->preco = 32;

    if (isset($cat)) {
        $cat->produtos()->save($p);
    }

    // Se eu fizer assim, ele vai retornar a categoria e os produtos que já tinham
    // antes de dá o save, por que na linha 107 é onde foi feito o with com find e
    // nesse momento não havia dado o save o novo produto.
    //return $cat->toJson();

    // Para retornar, também, o novo produto preciso fazer isso, ou sejam, recarregar os produtos das categorias:
    $cat->load('produtos');
    return $cat->toJson();
});

// As três últimas rotas tem como objetivo adicionar um novo produto a categoria.

<?php

use Illuminate\Database\Seeder;

class ProdutoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('produtos')->insert(
                    ['nome' => 'Camiseta Pólo', 'preco' => 100, 'estoque' => 4, 'categoria_id' => 1]
                );
                
        DB::table('produtos')->insert(
            ['nome' => 'Calça Jeans', 'preco' => 120, 'estoque' => 8, 'categoria_id' => 1]
        );

        DB::table('produtos')->insert(
            ['nome' => 'PC Gamer', 'preco' => 5.400, 'estoque' => 458, 'categoria_id' => 6]
        );

        DB::table('produtos')->insert(
            ['nome' => 'TV 50 Polegadas', 'preco' => 8.487, 'estoque' => 5, 'categoria_id' => 2]
        );
    }
}

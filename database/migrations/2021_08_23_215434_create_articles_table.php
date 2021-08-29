<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('article_name',256);
            $table->string('article_code',8);
            $table->decimal('unit_price');
            $table->string('image_source');
            $table->integer('for_sell_limit');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
        DB::table('articles')->insert(
            array(
                [
                    'article_name' => 'Saw',
                    'article_code'=>substr(md5(uniqid(rand(), true)),0,8),
                    'unit_price'=>11.95,
                    'image_source'=>'saw.jpg',
                    'for_sell_limit'=>100
                ],
                [
                    'article_name' => 'Hammer',
                    'article_code'=>substr(md5(uniqid(rand(), true)),0,8),
                    'unit_price'=>22.35,
                    'image_source'=>'hammer.jpg',
                    'for_sell_limit'=>100
                ],
                [
                    'article_name' => 'Screwdriver',
                    'article_code'=>substr(md5(uniqid(rand(), true)),0,8),
                    'unit_price'=>130.00,
                    'image_source'=>'screwdriver.jpg',
                    'for_sell_limit'=>10
                ],
                [
                    'article_name' => 'Marble saw',
                    'article_code'=>substr(md5(uniqid(rand(), true)),0,8),
                    'unit_price'=>240.50,
                    'image_source'=>'marble_saw.jpg',
                    'for_sell_limit'=>10
                ],
                [
                    'article_name' => 'Pushcart',
                    'article_code'=>substr(md5(uniqid(rand(), true)),0,8),
                    'unit_price'=>150.00,
                    'image_source'=>'pushcart.jpg',
                    'for_sell_limit'=>5
                ],
                [
                    'article_name' => 'Pliers',
                    'article_code'=>substr(md5(uniqid(rand(), true)),0,8),
                    'unit_price'=>18.80,
                    'image_source'=>'pliers.jpg',
                    'for_sell_limit'=>100
                ],
                [
                    'article_name' => 'Hydraulic jack',
                    'article_code'=>substr(md5(uniqid(rand(), true)),0,8),
                    'unit_price'=>190.00,
                    'image_source'=>'hydraulic_jack.jpg',
                    'for_sell_limit'=>10
                ],
                [
                    'article_name' => 'Water pump',
                    'article_code'=>substr(md5(uniqid(rand(), true)),0,8),
                    'unit_price'=>278.00,
                    'image_source'=>'water_pump.jpg',
                    'for_sell_limit'=>10
                ],
                [
                    'article_name' => 'Copper wire',
                    'article_code'=>substr(md5(uniqid(rand(), true)),0,8),
                    'unit_price'=>145.78,
                    'image_source'=>'copper_wire.jpg',
                    'for_sell_limit'=>50
                ],
                [
                    'article_name' => 'Grass trimmer',
                    'article_code'=>substr(md5(uniqid(rand(), true)),0,8),
                    'unit_price'=>80.00,
                    'image_source'=>'grass_trimmer.jpg',
                    'for_sell_limit'=>100
                ],
            )
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles');
    }
}

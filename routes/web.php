<?php

use App\Models\Article;
use App\Models\Order;
use App\Models\OrderArticle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

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

/*Route::get('/', function () {
    return view('welcome');
});*/
Route::get('/', function () {
    return view('order');
});
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('/articles',function(){
    return response()->json(DB::table('articles')->get());
});

Route::post('/create-order',function(Request $r){
    $result = [
        'success'=>true,
        'message'=>"You have done your order."
    ];

    $articles_ids = array_column($r->create_order,"id");
    $articles = Article::find($articles_ids);
    $order_articles = [];
    $total_amount_without_discount = 0;
    $total_amount_with_discount = 0;
    $total_units_article = 0;
    $id_pointer = "";
    $commit = false;

    if(count($articles_ids) == count($articles)){
        foreach($articles as $article){

            $quantity = (int) $r->create_order[$article->id]['quantity'];
            
            $discount = ($quantity >= 5  && $quantity <= 9 && $article->unit_price * $quantity >= 500) ? .85 : 1;
        
            $article->total_price = $article->unit_price * $quantity;
            $article->quantity = $quantity;
            $article->article_id = $article->id;
            $total_units_article += $quantity;
            $total_amount_without_discount += $article->total_price;
            $total_amount_with_discount += $article->total_price * $discount;

            $order_articles[] = array_merge(
                ["order_id"=>&$id_pointer],
                array_intersect_key($article->toArray(),array_flip(['article_id','quantity','total_price','unit_price']))
            );
        }


        DB::beginTransaction();

        try {

            $id_pointer = DB::table('orders')->insertGetId(
                [
                    'total_amount_without_discount'=>$total_amount_without_discount,
                    'total_amount_with_discount'=>$total_amount_with_discount,
                    'order_code'=>DB::raw("concat(date_format(now(),'%Y-%m'),'-','0000')"),
                    'total_kind_article'=>count($order_articles),
                    'total_units_article'=>$total_units_article,
                    'discount'=>$total_amount_without_discount-$total_amount_with_discount,
                    'created_at'=>DB::raw("now()"),
                    'updated_at'=>DB::raw("now()")
                ]
            );

            if($id_pointer && OrderArticle::insert($order_articles)){
                $commit = DB::commit();
                Order::where('id',$id_pointer)->update([
                    'order_code'=>DB::raw("concat(date_format(now(),'%Y-%m'),'-',right('0000{$id_pointer}',4))")
                ]);
                $result['order'] = Order::where('id',$id_pointer)->with('orderArticles.article')->first();
            }else{
                DB::rollBack();
                $result['success'] = false;
                $result['message'] = "During the processing our system got an error, try again.";
            }
        } catch (\Exception $e) {
            if(!$commit){
                DB::rollback();
                $result['success'] = false;
                $result['message'] = "During the processing our system got an error, try again.";
            }
        }
    }else{
        $result['success'] = false;
        $result['message'] = "It seems that you have included article item that we do not have in a our system.";
    }
    
    return response()->json($result);
});

Route::post("/order-list",function(){
    return response()->json(Order::with('orderArticles.article')->get());
});
<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class Sell2BrazilTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
    public function test_register_one_order(){
        $response = $this->post('/create-order',json_decode('{"create_order":{"1":{"id":1,"quantity":1}}}',true));
        $response->assertStatus(200)
        ->assertJson(json_decode('{"success":true,"order":{"order_articles":[{"article_id":1,"quantity":1,"article":{"id":1}}]}}',true));
    }
    public function test_get_all_articles(){
        $response = $this->post("/articles",[]);
        $response->assertStatus(200)
        ->decodeResponseJson();
    }
}

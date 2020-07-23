<?php

namespace Tests\Feature\Cart;

use Tests\TestCase;
use App\Models\User;
use App\Models\ProductVariation;

class CarteEmptyTest extends TestCase
{
    public function test_it_fails_if_the_user_is_unauthenticated()
    {
    	$this->json('DELETE', 'api/cart/empty')
        	->assertStatus(401);
    }

    public function test_it_fails_if_no_cart_cant_be_found_attached_with_this_user()
    {
    	$user = factory(User::class)->create();

      $this->actingAs($user,'api');

    	$this->json('DELETE', 'api/cart/empty')
        	->assertStatus(404);
    }

    public function test_it_deletes_all_the_products_from_the_cart()
    {
    	$user = factory(User::class)->create();

    	$user->cart()->syncWithoutDetaching(
    		$productVariation = factory(ProductVariation::class)->create(), [
    		'quantity' => $quantity = 1
    	],
        $anotherproductVariation = factory(ProductVariation::class)->create(), [
        'quantity' => $anotherquantity = 2
      ]
    );

      $this->actingAs($user,'api');

    	$response = $this->json('DELETE', "api/cart/empty");

    	$this->assertDatabaseMissing('cart_user', [
            'product_variation_id' => $productVariation->id,
            'quantity' => $quantity,
            'product_variation_id' => $anotherproductVariation->id,
            'quantity' => $anotherquantity
        ]);
    }


}
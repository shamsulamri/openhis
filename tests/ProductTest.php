<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProductTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }

	public function testRaceMaintenance()
	{
			$this->visit('/')
					->type('sa', 'username')
					->type('inknor', 'password')
					->press('Login')
					->visit('/products/create')
					->see('New Product')
					->type('_test_product_a', 'product_code')
					->type('Product A', 'product_name')
					->type('_test_product_c', 'product_conversion_code')
					->type('10', 'product_conversion_unit')
					->check('product_purchased')
					->type('25','product_purchase_price')
					->select('drugs', 'category_code')
					->press('Save')
					->see('Product A')

					->visit('/products/create')
					->see('New Product')
					->type('_test_product_b', 'product_code')
					->type('Product B', 'product_name')
					->type('_test_product_c', 'product_conversion_code')
					->check('product_purchased')
					->type('15','product_purchase_price')
					->select('drugs', 'category_code')
					->press('Save')
					->see('Product B')

					->visit('/products/create')
					->see('New Product')
					->type('_test_product_c', 'product_code')
					->type('Product C', 'product_name')
					->check('product_sold')
					->select('drugs', 'category_code')
					->press('Save')
					->see('Product C');
	}
}

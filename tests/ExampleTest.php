<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExampleTest extends TestCase
{
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testBasicExample()
    {
        $this->visit('/')
             ->see('Healthcare Information System');
    }

	public function testRegister()
	{
			$this->visit('/')
					->click('Register')
					->seePageIs('/register');
	}

	public function testRaceMaintenance()
	{
			$this->visit('/')
					->type('sa', 'username')
					->type('inknor', 'password')
					->press('Login')
					->visit('/races/create')
					->see('New Race')
					->type('_test_code', 'race_code')
					->type('_test_name', 'race_name')
					->press('Save')
					->see('_test_name')
					->visit('/races/delete/_test_code')
					->press('Delete')
					->dontSee('_test_name');
	}
}

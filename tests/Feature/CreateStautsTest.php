<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateStautsTest extends TestCase {

	use RefreshDatabase;
	/**
     @test
     **/

		public function guests_users_can_not_create_statuses(){
			
			$response = $this->post(route('statuses.store'), ['body'=>'mi primer status']);
			//dd($response->content());
			$response->assertRedirect('login');
		}

    public function an_authenticated_user_can_create_statuses(){

			$this->withoutExceptionHandling();
			#1. Given Teniendo un usuario autenticado.
			$user = factory(User::class)->create(['email'=>'vieyrapez@gmail.com']);
			$this->actingAs($user);
			#2. when Cuando hace un post request a status.
			$response = $this->post(route('statuses.store'), ['body'=>'mi primer status']);
			$response->assertJson([
				'body'=> 'mi primer status'
			]);


			#3. Then Entonces veo un nuevo estado en la base de datos.
			$this->assertDatabaseHas('statuses',[
				'user_id' => $user->id,
				'body' => 'mi primer status'
			]);
    }
}

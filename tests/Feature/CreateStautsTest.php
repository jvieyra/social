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
		$response = $this->postJson(route('statuses.store'), ['body'=>'mi primer status']);
		$response->assertJson([
			'body'=> 'mi primer status'
		]);


		#3. Then Entonces veo un nuevo estado en la base de datos.
		$this->assertDatabaseHas('statuses',[
			'user_id' => $user->id,
			'body' => 'mi primer status'
		]);
	}

	public function a_status_requires_a_body(){
		$user = factory(User::class)->create();
		$this->actingAs($user);
		$response = $this->postJson(route('statuses.store'), ['body'=>'mi primer status']);

		$response->assertStatus(422);
		$response->assertJsonStructure()([
			'message','errors'=>['body']
		]);

	}

	public function a_status_body_requires_a_minimum_length(){
		$user = factory(User::class)->create();
		$this->actingAs($user);
		$response = $this->postJson(route('statuses.store'), ['body'=>'asdf']);

		$response->assertStatus(422);
		$response->assertJsonStructure()([
			'message','errors'=>['body']
		]);

	}
		

}

<?php

namespace Tests\Feature\Controllers;

use App\Models\Friend;
use App\Models\User;
use App\Repositories\FriendRepository\FriendRepositoryInterface;
use Database\Seeders\TestDatabaseSeeder;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FriendControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private FriendRepositoryInterface $friendRepository;
    public function setUp(): void
    {
        parent::setUp();
        $this->seed(TestDatabaseSeeder::class);
        $this->user = User::find(1);
        $this->friendRepository = resolve(FriendRepositoryInterface::class);
    }

    public function testCantSendFriendRequestWithoutAuth()
    {
        $response = $this->post(route('friends.send_or_accept'), [ 'id' => 10]);

        $response->assertStatus(401)
            ->assertJson([
                'status' => 'error',
                'message' => 'Unauthenticated.'
            ]);
    }

    public function testCantSendFriendRequesWithoutParamId()
    {
        $response = $this->withToken($this->user->api_token)
            ->post(route('friends.send_or_accept'));

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'error',
                'message' => 'invalid data',
                'data' => [
                    'id' => [
                        'The id field is required.'
                    ]
                ]
            ]);
    }

    public function testCanSendFriendRequestToUserWithoutRealtionship()
    {
        $targetUserId = 10;

        $response = $this->withToken($this->user->api_token)
            ->post(route('friends.send_or_accept'), [ 'id' => $targetUserId]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'ok'
            ]);

        $friend = $this->friendRepository->getFriendship($this->user->id, $targetUserId);
        $this->assertEquals($this->friendRepository->getStatusSent(), $friend->status);
    }

    public function testCanSendFriendRequestToFriendWithRejectedStatus()
    {
        $targetUserId = 4;
        $sourceUserId = 8;

        $response = $this->withToken($this->user->api_token)
            ->post(route('friends.send_or_accept'), [ 'id' => $targetUserId]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'ok'
            ]);

        $friend = $this->friendRepository->getFriendship($this->user->id, $targetUserId);
        $this->assertEquals($this->friendRepository->getStatusSent(), $friend->status);


        $response = $this->withToken($this->user->api_token)
            ->post(route('friends.send_or_accept'), [ 'id' => $sourceUserId]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'ok'
            ]);

        $friend = $this->friendRepository->getFriendship($this->user->id, $sourceUserId);
        $this->assertEquals($this->friendRepository->getStatusSent(), $friend->status);
    }

    public function testCanSendFriendRequestToFriendWithDeletedStatus()
    {
        $targetUserId = 5;
        $sourceUserId = 9;

        $response = $this->withToken($this->user->api_token)
            ->post(route('friends.send_or_accept'), [ 'id' => $targetUserId]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'ok'
            ]);

        $friend = $this->friendRepository->getFriendship($this->user->id, $targetUserId);
        $this->assertEquals($this->friendRepository->getStatusSent(), $friend->status);


        $response = $this->withToken($this->user->api_token)
            ->post(route('friends.send_or_accept'), [ 'id' => $sourceUserId]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'ok'
            ]);


        $friend = $this->friendRepository->getFriendship($this->user->id, $sourceUserId);
        $this->assertEquals($this->friendRepository->getStatusSent(), $friend->status);
    }

    public function testCanAcceptFriendRequest()
    {
        $targetUserId = 6;

        $response = $this->withToken($this->user->api_token)
            ->post(route('friends.send_or_accept'), [ 'id' => $targetUserId]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'ok'
            ]);

        $friend = $this->friendRepository->getFriendship($this->user->id, $targetUserId);
        $this->assertEquals($this->friendRepository->getStatusActive(), $friend->status);
    }

    public function testCantSendFriendRequestToFriendWithSentStatus()
    {
        $targetUserId = 2;

        $response = $this->withToken($this->user->api_token)
            ->post(route('friends.send_or_accept'), [ 'id' => $targetUserId]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'error',
                'message' => 'The user already has your request'
            ]);
    }

    public function testCantSendFriendRequestToYourself()
    {
        $targetUserId = $this->user->id;

        $response = $this->withToken($this->user->api_token)
            ->post(route('friends.send_or_accept'), [ 'id' => $targetUserId]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'error',
                'message' => 'Can\'t send a friend request to yourself'
            ]);
    }
    public function testCantSendFriendRequestToNonExistentUser()
    {
        $targetUserId = 11;

        $response = $this->withToken($this->user->api_token)
            ->post(route('friends.send_or_accept'), [ 'id' => $targetUserId]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'error',
                'message' => 'Can\'t add this user to friends as user not found'
            ]);
    }

    public function testCantCancelFriendRequestWithoutAuth()
    {
        $response = $this->post(route('friends.cancel_or_reject'), [ 'id' => 2]);

        $response->assertStatus(401)
            ->assertJson([
                'status' => 'error',
                'message' => 'Unauthenticated.'
            ]);
    }

    public function testCantCancelFriendRequesWithoutParamId()
    {
        $response = $this->withToken($this->user->api_token)
            ->post(route('friends.send_or_accept'));

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'error',
                'message' => 'invalid data',
                'data' => [
                    'id' => [
                        'The id field is required.'
                    ]
                ]
            ]);
    }
}

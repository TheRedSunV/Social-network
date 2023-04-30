<?php


namespace App\Repositories\UserRepository;


use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getAuthUser(): ?User
    {
        $token = request()->bearerToken();
        if (!$token) {
            $token = request()->input('api_token');
        }

        return $this->user->where('api_token', $token)->first();
    }

    public function find(int $id): ?User
    {
        return $this->user->find($id);
    }
}

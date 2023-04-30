<?php

declare(strict_types=1);

namespace App\Repositories\FriendRepository;

use App\Models\Friend;

class FriendRepository implements FriendRepositoryInterface
{
    private Friend $friend;

    public function __construct(Friend $friend)
    {
        $this->friend = $friend;
    }

    public function find(int $id)
    {
        return $this->friend->find($id);
    }
    public function getAllByAuthUser($authUser)
    {
        return $authUser->friends();
    }

    public function create(array $data): void
    {
        $this->friend->create($data);
    }

    public function getFriendship(int $sourceUserId, int $targetUserId): ?Friend
    {
        return $this->friend->where([
            'source_id' => $sourceUserId,
            'target_id' => $targetUserId
        ])
            ->orWhere([
                ['source_id', $targetUserId],
                ['target_id', $sourceUserId]
            ])
            ->first();
    }

    public function getStatusSent(): string
    {
        return $this->friend::STATUS_SENT;
    }

    public function getStatusRejected(): string
    {
        return $this->friend::STATUS_REJECTED;
    }

    public function getStatusActive(): string
    {
        return $this->friend::STATUS_ACTIVE;
    }

    public function getStatusDeleted(): string
    {
        return $this->friend::STATUS_DELETED;
    }

    public function getStatuses(): array
    {
        return $this->friend::STATUSES;
    }

    public function createFriendRequest(int $sourceUserId, int $targetUserId): void
    {
        $this->friend->create([
            'source_id' => $sourceUserId,
            'target_id' => $targetUserId
        ]);
    }

    public function updateFriendRequest(int $friendId): void
    {
        $friend = $this->find($friendId);
        $friend->update([
            'status' => $this->getStatusSent()
        ]);
    }

    public function acceptFriendRequest(int $friendId): void
    {
        $friend = $this->find($friendId);
        $friend->update([
            'status' => $this->getStatusActive()
        ]);
    }

    public function cancelOrRejectedFriendRequest(int $friendId)
    {
        $this->friend->update([
            'status' => $this->getStatusRejected()
        ]);
    }
}

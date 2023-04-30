<?php

declare(strict_types=1);

namespace App\Services\Friend;

use App\Exceptions\Reportable\FriendException;
use App\Models\User;
use App\Repositories\FriendRepository\FriendRepositoryInterface;
use App\Repositories\UserRepository\UserRepositoryInterface;

class FriendService implements FriendServiceInterface
{
    private FriendRepositoryInterface $friendRepository;
    private UserRepositoryInterface $userRepository;
    private ?User $authUser;

    public function __construct(FriendRepositoryInterface $friendRepository, UserRepositoryInterface $userRepository)
    {
        $this->friendRepository = $friendRepository;
        $this->userRepository = $userRepository;
        $this->authUser = $this->userRepository->getAuthUser();
    }

    /**
     * @param $targetUserId
     * @throws FriendException
     */
    public function sendOrAcceptFriendRequest($targetUserId)
    {
        if ($this->authUser->id == $targetUserId) {
            throw new FriendException(__('exceptions.friends.friend_request_to_self'));
        }

        if (!$this->userRepository->find($targetUserId)) {
            throw new FriendException(__('exceptions.friends.target_user_not_found'));
        }

        /**
         * TODO: check blacklist
         */

        $friend = $this->friendRepository->getFriendship($this->authUser->id, $targetUserId);

        if (!$friend) {
            $this->friendRepository->createFriendRequest($this->authUser->id,$targetUserId);

        } elseif ($friend->status == $this->friendRepository->getStatusActive()) {
            throw new FriendException(__('exceptions.friends.already_friend'));

        } elseif ($friend->target_id == $targetUserId and $friend->status == $this->friendRepository->getStatusSent()) {
            throw new FriendException(__('exceptions.friends.already_has_request'));

        } elseif ($friend->status == $this->friendRepository->getStatusRejected()
            or $friend->status == $this->friendRepository->getStatusDeleted()) {
            $this->friendRepository->updateFriendRequest($friend->id);

        } elseif ($friend->target_id == $this->authUser->id
            and $friend->status == $this->friendRepository->getStatusSent()) {
            $this->friendRepository->acceptFriendRequest($friend->id);
        }
    }

    /**
     * @param $targetUserId
     * @throws FriendException
     */
    public function cancelOrRejectFriendRequest($targetUserId)
    {
        if ($this->authUser->id == $targetUserId) {
            throw new FriendException(__('exceptions.friends.invalid_action'));
        }

        if (!$this->userRepository->find($targetUserId)) {
            throw new FriendException(__('exceptions.friends.target_user_not_found'));
        }

        /**
         * TODO: check blacklist
         */

        $friend = $this->friendRepository->getFriendship($this->authUser->id, $targetUserId);

        if (!$friend) {
            throw new FriendException(__('exceptions.friends.no_friend_request'));

        } elseif ($friend->status == $this->friendRepository->getStatusActive()) {
            throw new FriendException(__('exceptions.friends.already_friend'));

        } elseif ($friend->status != $this->friendRepository->getStatusSent()) {
            throw new FriendException(__('exceptions.friends.no_friend_request'));

        } else {
            $this->friendRepository->cancelOrRejectedFriendRequest($friend->id);
        }
    }

    /**
     * @param $targetUserId
     * @throws FriendException
     */
    public function deleteFriend($targetUserId)
    {
        if ($this->authUser->id == $targetUserId) {
            throw new FriendException(__('exceptions.friends.invalid_action'));
        }

        if (!$this->userRepository->find($targetUserId)) {
            throw new FriendException(__('exceptions.friends.target_user_not_found'));
        }

        /**
         * TODO: check blacklist
         */

        $friend = $this->friendRepository->getFriendship($this->authUser->id, $targetUserId);

        if (!$friend or $friend->status !== $this->friendRepository->getStatusActive()) {
            throw new FriendException(__('exceptions.friends.not_a_friend'));
        }

        $friend->update([
            'status' => $this->friendRepository->getStatusDeleted()
        ]);

    }

    /**
     * @param int $targetUserId
     * @return \App\Models\Friend|null
     */
    public function getFriendship(int $targetUserId)
    {
        return $this->friendRepository->getFriendship($this->authUser->id, $targetUserId);
    }

    /**
     * @return mixed
     */
    public function getAllByAuthUser()
    {
        return $this->friendRepository->getAllByAuthUser($this->authUser);
    }

}

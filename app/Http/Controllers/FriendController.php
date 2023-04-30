<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Exceptions\Reportable\FriendException;
use App\Responses\ApiError;
use App\Responses\ApiResponseInterface;
use App\Responses\ApiSuccess;
use App\Services\Friend\FriendServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FriendController extends Controller
{
    private FriendServiceInterface $friendService;

    public function __construct(FriendServiceInterface $friendService)
    {
        $this->friendService = $friendService;
    }

    /**
     * @return ApiResponseInterface
     */
    public function all(): ApiResponseInterface
    {
        $friends = $this->friendService->getAllByAuthUser();

        return new ApiSuccess('ok', $friends);
    }

    /**
     * @param Request $request
     * @return ApiResponseInterface
     */
    public function get(Request $request): ApiResponseInterface
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:1'
        ]);

        if($validator->fails()){
            return new ApiError('Invalid data', $validator->errors());
        }

        try{
            $friend = $this->friendService->getFriendship((int) $request->id);
        }catch(\Throwable $e){
            return new ApiError('Something went wrong');
        }

        return new ApiSuccess('ok', $friend);
    }

    /**
     * @param Request $request
     * @return ApiResponseInterface
     */
    public function sendOrAcceptFriendRequest(Request $request): ApiResponseInterface
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:1'
        ]);

        if($validator->fails()){
            return new ApiError('invalid data', $validator->errors());
        }

        try{
           $this->friendService->sendOrAcceptFriendRequest($request->id);
        } catch(FriendException $e){
            return new ApiError($e->getMessage());
        } catch(\Throwable $e){
            return new ApiError('Something went wrong');
        }

        return new ApiSuccess('ok');
    }

    /**
     * @param Request $request
     * @return ApiResponseInterface
     */
    public function cancelOrRejectFriendRequest(Request $request): ApiResponseInterface
    {
        $validator = Validator::make($request->all(), [
           'id' => 'required|integer|min:1'
        ]);

        if($validator->fails()){
            return new ApiError('invalid data', $validator->errors());
        }

        try{
           $this->friendService->cancelOrRejectFriendRequest($request->id);
        } catch(FriendException $e){
            return new ApiError($e->getMessage());
        } catch(\Throwable $e){
            return new ApiError('Something went wrong');
        }

        return new ApiSuccess('ok');
    }

    /**
     * @param Request $request
     * @return ApiResponseInterface
     */
    public function deleteFriend(Request $request): ApiResponseInterface
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:1'
        ]);

        if($validator->fails()){
            return new ApiError('invalid data', $validator->errors());
        }

        try{
            $this->friendService->deleteFriend($request->id);
        } catch(FriendException $e){
            return new ApiError($e->getMessage());
        } catch(\Throwable $e){
            return new ApiError('Something went wrong');
        }

        return new ApiSuccess('ok');
    }


}

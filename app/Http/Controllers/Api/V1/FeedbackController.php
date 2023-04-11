<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Api\FeedbackStoreRequest;
use App\Library\Utilities;
use App\Models\Feedback;
use App\Models\Store;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Throwable;

class FeedbackController extends ApiController
{
    public function store(FeedbackStoreRequest $request, Store $store): JsonResponse
    {
        try {
            $user = Auth::user();

            Feedback::create([
                'user_id' => $user->id,
                'store_id' => $store->id,
                'referencable_id' => $request->input('reference_id'),
                'referencable_type' => Feedback::REFERENCES[$request->input('reference')],
                'content' => $request->input('content')
            ]);

            $this->statusCode = 200;
            $this->statusMessage = "Feedback successfully saved.";
        } catch (Throwable $throwable) {
            Utilities::generateCustomErrorLog(
                "Feedback_Save_Failed",
                $throwable->getMessage(),
                $throwable->getLine()
            );
        }

        return apiResponse(
            $this->statusCode,
            $this->statusMessage,
            $this->responseData
        );
    }
}

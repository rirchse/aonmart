<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Library\Utilities;

class PaymentController extends Controller
{
    public function getPaymentMethods()
    {
        return apiResponse(
            200,
            "Payment methods successfully fetched.",
            Utilities::availablePaymentMethods()
        );
    }
}

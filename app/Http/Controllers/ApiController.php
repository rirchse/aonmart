<?php

namespace App\Http\Controllers;

use App\Traits\ExceptionHandlerTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Throwable;

class ApiController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, ExceptionHandlerTrait;

    protected int $itemPerRequest = 15;

    protected int $statusCode = 500;

    protected string $statusMessage = 'Something wrong! Please, try again.';

    protected array $responseData = [];
}

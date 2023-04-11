<?php

namespace App\Http\Controllers;

use App\Library\Utilities;
use App\Models\Store;
use App\Traits\ExceptionHandlerTrait;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, ExceptionHandlerTrait;

    protected int $itemPerRequest = 15;
    protected int $itemPerPage = 10;

    protected string $success = 'success';
    protected string $error = 'error';

    protected Store|null $store = null;
}

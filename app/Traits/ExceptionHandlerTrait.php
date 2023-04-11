<?php

namespace App\Traits;

use Exception;

trait ExceptionHandlerTrait
{
    public bool $exception_status;
    public int $status_code = 500;
    public string $status_message = "Unknown error.";
    public array $status_data = [];

    public function exceptionArise(): bool
    {
        return $this->exception_status === true;
    }

    public function throwException(string $message = "Something wrong! Please, contact our support team.", int $code = 500)
    {
        $this->exception_status = true;
        $this->status_code = $code;
        $this->status_message = $message;
        throw new Exception($message);
    }

    public function setError(string $message = "Something wrong! Please, contact our support team.", int $code = 500)
    {
        $this->status_code = $code;
        $this->status_message = $message;
    }

    public function clearException(string $message)
    {
        $this->exception_status = false;
        $this->status_code = 200;
        $this->status_message = $message;
    }

    public function flashSessionKey()
    {
        return ($this->exception_status === true) or ($this->status_code != 200) ? 'error' : 'success';
    }
}

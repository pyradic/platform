<?php

namespace Pyro\Platform\Exception;

use Pyro\Platform\Http\HttpStatus;
use Symfony\Component\HttpKernel\Exception\HttpException;

class NotImplementedHttpException extends HttpException
{
    public function __construct(\Throwable $previous = null, array $headers = [], ?int $code = 0)
    {
        parent::__construct(HttpStatus::NOT_IMPLEMENTED, 'Functionality has not yet been implemented', $previous, $headers, $code);
    }

    public static function make(\Throwable $previous = null, array $headers = [], ?int $code = 0)
    {
        return new static($previous, $headers,$code);
    }

    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }



}

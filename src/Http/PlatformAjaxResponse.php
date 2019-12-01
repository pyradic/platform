<?php

namespace Pyro\Platform\Http;

use Illuminate\Container\Container;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\HeaderBag;

class PlatformAjaxResponse implements Responsable
{
    /** @var mixed|null */
    protected $data;

    /** @var bool */
    protected $success = true;

    /** @var \Symfony\Component\HttpFoundation\HeaderBag */
    protected $headers;

    /** @var \Pyro\Platform\Platform */
    protected $platform;

    /** @var \Pyro\Platform\Http\HttpStatus */
    protected $status;

    /** @var array */
    protected $debugData = [];

    /** @var string */
    protected $message = 'OK';

    /** @var int|null */
    protected $jsonOptions;

    /** @var string */
    protected $locale;

    /**
     * PlatformAjaxResponse constructor.
     *
     * @param null                               $data
     * @param int|\Pyro\Platform\Http\HttpStatus $status
     * @param array                              $headers
     */
    public function __construct($data = null, $status = 200, $headers = [])
    {
        $this->data    = $data;
        $this->status  = HttpStatus::get($status);
        $this->headers = new HeaderBag($headers);
        $this->locale = config('app.locale');
        $this->platform = $this->getContainer()->make('platform');
    }

    public static function create($data = null, $status = 200, $headers = [])
    {
        return new static($data, $status, $headers);
    }

    public static function error($error = null)
    {
        return static::create()->asError($error);
    }

    /**
     * @param null|string|string[] $key
     * @param null|mixed           $value
     *
     * @return void
     */
    public function header()
    {
        $args = func_get_args();
        $num  = func_num_args();
        if ($num === 1) {
            return $this->headers->get($args[ 0 ]);
        }
        if ($num === 2 && $args[ 1 ] === null) {
            $this->headers->remove($args[ 0 ]);
        }
        if ($num === 2) {
            $this->headers->set($args[ 0 ], $args[ 1 ]);
        }
    }

    public function with($key, $value = null)
    {
        data_set($this->data, $key, $value);
        return $this;
    }

    /**
     * @param HttpStatus |int $status
     * @param null            $message
     *
     * @return $this
     */
    public function asError($status, $message = null)
    {
        if ($message instanceof \Exception) {
            $message = $this->isDebug() ? (string)$message : $message->getMessage();
        }
        if ($message === null) {
            $message = 'Unknown error';
        }
        $this->message = $message;
        $this->status  = HttpStatus::get($status);
        return $this;
    }

    protected function getContainer()
    {
        return Container::getInstance();
    }

    protected function isDebug()
    {
        return config('app.debug');
    }

    /**
     * @inheritDoc
     */
    public function toResponse($request)
    {
        $data = collect();
        $data->put('success', $this->success);
        $data->put('data', $this->data);
        $data->put('locale', $this->locale);
        $data->put('message', $this->message);
        $data->put('status', $this->status);
//        $data->put('platform', $this->platform->getAjaxData());
        if($this->debugData){
            $data->put('debug_data', $this->debugData);
        }
        return JsonResponse::create($data, $this->status->getValue(), $this->headers->all());
    }

    public function withData($data)
    {
        $this->data = $data;
        return $this;
    }

    public function withHeaders(array $headers)
    {
        $this->headers->add($headers);
        return $this;
    }

    /**
     * @param int|\Pyro\Platform\Http\HttpStatus $status
     *
     * @return $this
     */
    public function withStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    public function withDebugData($debugData)
    {
        $this->debugData = $debugData;
        return $this;
    }

    public function withMessage(string $message)
    {
        $this->message = $message;
        return $this;
    }

    public function withJsonOptions(int $jsonOptions)
    {
        $this->jsonOptions = $jsonOptions;
        return $this;
    }

    public function withLocale(string $locale)
    {
        $this->locale = $locale;
        return $this;
    }


}

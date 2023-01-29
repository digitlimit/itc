<?php

namespace ITC\Insurance\Http;

class MultipleRequest extends BaseHttp
{
    public function __construct(
        /**
         * The full url or path
         * 
         * @var string
         */
        protected string $url = '',

        /**
         * Request method 
         * 
         * @var string
         */
        protected string $method = '',

        /**
         * Request parameters
         * 
         * @var array
         */
        protected array $params = []
    ){}
}
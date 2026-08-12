<?php

namespace App\Http\Middleware;

// use Fideloper\Proxy\TrustProxies as Middleware;
use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    /**
     * The trusted proxies for this application.
     *
     * @var array|string
     */
    protected $proxies = [
        '10.128.0.35', // private/local with public IP 34.67.27.38 [Stage Nginx]
        '10.128.0.61', // private/local with public IP 34.67.67.65 [QA Nginx]

        '10.128.15.199', // private/local with public IP 35.225.195.54 [Production HGA]
        '127.0.0.1' // private/local IP [Production Responsive Servers - NIF, JCF, etc.]
    ];

    /**
     * The headers that should be used to detect proxies.
     *
     * @var int
     */
    // protected $headers = Request::HEADER_X_FORWARDED_ALL;
	
	protected $headers = Request::HEADER_X_FORWARDED_FOR
    | Request::HEADER_X_FORWARDED_HOST
    | Request::HEADER_X_FORWARDED_PORT
    | Request::HEADER_X_FORWARDED_PROTO
    | Request::HEADER_X_FORWARDED_AWS_ELB;
}

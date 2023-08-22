<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Foundation\Http\Middleware\TransformsRequest;

class EmptyStringsToNull extends TransformsRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    protected function transform($key, $value)
    {
        if(is_string($value) && !in_array($key,  ['abc'], true)) {
            $value = $value === '' ? null : $value;
        }
        return $value;
    }
}

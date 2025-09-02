<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
class FilterRequest
{
    public function handle(Request $request, Closure $next)
    {
            $input = $request->all();
            array_walk_recursive($input, function (&$value) {
                if (is_string($value)) {
                    $value = htmlspecialchars_decode($value);
                    $value = preg_replace('/<\s*script\b[^>]*>(.*?)<\s*\/\s*script\s*>/is', '', $value);
                    $value = str_replace(['&lt;', '&gt;', 'javascript', 'script','alert'], '', $value);
                }
            });
            $request->merge($input);
            return $next($request);
        
    }
}

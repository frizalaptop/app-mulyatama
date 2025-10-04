<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MinifyHtml
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($response instanceof Response && $response->headers->get('Content-Type') === 'text/html; charset=UTF-8') {
            $output = $response->getContent();

            // Minify: hapus spasi/line break berlebih antar tag
            $output = preg_replace('/>\s+</', '><', $output);

            // Opsional: hapus spasi di awal/akhir baris
            $output = preg_replace('/\s+/', ' ', $output);

            $response->setContent($output);
        }

        return $response;
    }
}

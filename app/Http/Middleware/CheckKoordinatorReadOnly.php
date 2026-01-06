<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckKoordinatorReadOnly
{
    /**
     * Handle an incoming request.
     *
     * Block koordinator from modifying data (POST, PUT, PATCH, DELETE requests)
     * Koordinator is READ-ONLY - can only view/monitor
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        
        // If user is koordinator (and not also admin)
        if ($user && $user->isKoordinator() && !$user->isAdmin()) {
            // Block any modifying requests (POST, PUT, PATCH, DELETE)
            if ($request->isMethod('post') || 
                $request->isMethod('put') || 
                $request->isMethod('patch') || 
                $request->isMethod('delete')) {
                
                // Return error response
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Koordinator hanya bisa memonitor. Tidak bisa melakukan perubahan data.'
                    ], 403);
                }
                
                return redirect()->back()
                    ->with('error', 'Koordinator hanya bisa memonitor. Tidak bisa melakukan perubahan data.');
            }
        }
        
        return $next($request);
    }
}

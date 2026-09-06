<?php

namespace App\Http\Middleware;

use App\Models\University;
use App\Services\Tenant\TenantManager;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IdentifyTenant
{
    public function __construct(
        protected TenantManager $tenantManager
    ) {}

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $university = null;

        // 1. Check HTTP Headers: X-University-Code or X-University-ID
        if ($code = $request->header('X-University-Code')) {
            $university = University::where('code', strtoupper($code))->first();
        } elseif ($id = $request->header('X-University-ID')) {
            $university = University::find($id);
        }

        // 2. Check Host / Subdomain / Custom Domain if header was not provided
        if (!$university) {
            $host = $request->getHost();

            // Check custom domain first (e.g. portal.novica.edu.ng)
            $university = University::where('custom_domain', $host)->first();

            // If not found, extract subdomain (e.g. novica.localhost or novica.netzertech.com)
            if (!$university) {
                $parts = explode('.', $host);
                if (count($parts) >= 2) {
                    $subdomain = $parts[0];
                    if (!in_array($subdomain, ['www', 'api', 'admin', 'localhost', '127'])) {
                        $university = University::where('subdomain', strtolower($subdomain))->first();
                    }
                }
            }
        }

        // 3. Fallback to Authenticated User's University if available
        if (!$university && $request->user() && $request->user()->university_id) {
            $university = University::find($request->user()->university_id);
        }

        if ($university) {
            if (!$university->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'The requested university portal is currently deactivated. Please contact support.',
                ], Response::HTTP_FORBIDDEN);
            }

            $this->tenantManager->setTenant($university);
            $request->attributes->set('tenant', $university);
        }

        return $next($request);
    }
}

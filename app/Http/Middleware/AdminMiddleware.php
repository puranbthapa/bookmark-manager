<?php

/**
 * ================================================================================
 * ADMIN MIDDLEWARE - ACCESS CONTROL
 * ================================================================================
 *
 * 🏢 VENDOR: Eastlink Cloud Pvt. Ltd.
 * 👨‍💻 AUTHOR: Developer Team
 * 📅 CREATED: October 2025
 * 📧 CONTACT: puran@eastlink.net.np
 * 📞 PHONE: +977-01-4101181
 * 📱 DEVELOPER: +977-9801901140
 * 💼 BUSINESS: +977-9801901141
 * 🏢 ADDRESS: Tripureshwor, Kathmandu, Nepal
 *
 * 📋 DESCRIPTION:
 * Admin access control middleware that ensures only administrators
 * can access admin panel routes and functionalities.
 *
 * 🎯 SECURITY FEATURES:
 * - Role-based access control
 * - Admin permission verification
 * - Unauthorized access prevention
 * - Secure redirect handling
 *
 * ⚖️ LICENSE: Commercial Enterprise License
 * ================================================================================
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to access this area.');
        }

        // Check if user has admin role
        if (!Auth::user()->hasRole('admin')) {
            abort(403, 'Access denied. Administrator privileges required.');
        }

        return $next($request);
    }
}

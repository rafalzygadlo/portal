<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


$domain = config('app.business_domain');
if (empty($domain)) 
{
    throw new \Exception('SYSTEM ERROR: The DOMAIN_NAME value in .env is empty. Configure it so subdomains work correctly.');
}

Route::domain($domain)->group(base_path('routes/web/main.php'));
Route::domain('{subdomain}.' . $domain)->group(base_path('routes/web/subdomain.php'));




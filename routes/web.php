<?php

use App\Helpers\Router;
use App\Middleware\AuthRequired;
use App\Middleware\AdminRequired;
use App\Middleware\VerifyCSRF;

// Public routes
Router::get('/', 'HomeController@index');
Router::get('tools', 'HomeController@tools');
Router::get('tools/{slug}', 'HomeController@tool');
Router::get('categories', 'HomeController@categories');
Router::get('pricing', 'HomeController@pricing');
Router::get('how-it-works', 'HomeController@howItWorks');
Router::get('about', 'HomeController@about');
Router::get('contact', 'HomeController@contact');
Router::get('faq', 'HomeController@faq');
Router::get('privacy', 'HomeController@privacy');
Router::get('terms', 'HomeController@terms');

// Authentication
Router::get('login', 'AuthController@showLogin');
Router::post('login', 'AuthController@login', [VerifyCSRF::class]);
Router::get('register', 'AuthController@showRegister');
Router::post('register', 'AuthController@register', [VerifyCSRF::class]);
Router::get('logout', 'AuthController@logout');
Router::get('forgot-password', 'AuthController@showForgotPassword');
Router::post('forgot-password', 'AuthController@forgotPassword', [VerifyCSRF::class]);

// Social Logins (Google & Apple)
Router::get('auth/google', 'AuthController@googleRedirect');
Router::get('auth/google/mock-callback', 'AuthController@googleMockCallback');
Router::post('auth/google/mock-submit', 'AuthController@googleMockSubmit', [VerifyCSRF::class]);
Router::get('auth/apple', 'AuthController@appleRedirect');
Router::get('auth/apple/mock-callback', 'AuthController@appleMockCallback');
Router::post('auth/apple/mock-submit', 'AuthController@appleMockSubmit', [VerifyCSRF::class]);

// Installer
Router::get('install', 'InstallController::showInstall');
Router::post('install', 'InstallController::runInstall');

// User Dashboard & My Pages (Protected by AuthRequired)
Router::get('dashboard', 'DashboardController@index', [AuthRequired::class]);
Router::get('profile', 'DashboardController@profile', [AuthRequired::class]);
Router::post('profile', 'DashboardController@updateProfile', [AuthRequired::class, VerifyCSRF::class]);
Router::get('token-history', 'DashboardController@tokenHistory', [AuthRequired::class]);
Router::post('claim-free-tokens', 'DashboardController@claimFreeTokens', [AuthRequired::class, VerifyCSRF::class]);
Router::get('my-urls', 'DashboardController@myUrls', [AuthRequired::class]);
Router::get('my-invoices', 'DashboardController@myInvoices', [AuthRequired::class]);
Router::get('saved-notes', 'DashboardController@savedNotes', [AuthRequired::class]);

// URL Redirection
Router::get('go/{slug}', 'UrlController@customRedirect');
Router::get('s/{code}', 'UrlController@shortRedirect');

// Admin Panel (Protected by AuthRequired, AdminRequired)
Router::get('admin', 'AdminController@index', [AuthRequired::class, AdminRequired::class]);
Router::get('admin/users', 'AdminController@users', [AuthRequired::class, AdminRequired::class]);
Router::get('admin/tools', 'AdminController@tools', [AuthRequired::class, AdminRequired::class]);
Router::post('admin/tools/update', 'AdminController@updateTool', [AuthRequired::class, AdminRequired::class, VerifyCSRF::class]);
Router::get('admin/payments', 'AdminController@payments', [AuthRequired::class, AdminRequired::class]);
Router::get('admin/coupons', 'AdminController@coupons', [AuthRequired::class, AdminRequired::class]);
Router::post('admin/coupons/add', 'AdminController@addCoupon', [AuthRequired::class, AdminRequired::class, VerifyCSRF::class]);
Router::get('admin/settings', 'AdminController@settings', [AuthRequired::class, AdminRequired::class]);
Router::post('admin/settings/update', 'AdminController@updateSettings', [AuthRequired::class, AdminRequired::class, VerifyCSRF::class]);
Router::get('admin/branding', 'AdminController@branding', [AuthRequired::class, AdminRequired::class]);
Router::post('admin/branding/update', 'AdminController@updateBranding', [AuthRequired::class, AdminRequired::class, VerifyCSRF::class]);
Router::get('admin/code-injection', 'AdminController@codeInjection', [AuthRequired::class, AdminRequired::class]);
Router::post('admin/code-injection/update', 'AdminController@updateCodeInjection', [AuthRequired::class, AdminRequired::class, VerifyCSRF::class]);

// API Endpoints
Router::post('api/tools/process', 'ToolApiController@process');
Router::post('api/payments/webhook', 'PaymentController@webhook');
Router::post('api/payments/simulate-checkout', 'PaymentController@simulateCheckout', [AuthRequired::class, VerifyCSRF::class]);
Router::post('api/coupons/validate', 'PaymentController@validateCoupon');
Router::post('api/shorten', 'UrlController@shorten');
Router::post('api/shorten-custom', 'UrlController@shortenCustom');
Router::post('api/urls/edit', 'UrlController@editCustom');

// Instagram Comment Picker (Expanded - Mock / Real integration)
Router::get('api/instagram/connect', 'InstagramController@connect');
Router::get('api/instagram/posts', 'InstagramController@posts');
Router::post('api/instagram/draw', 'InstagramController@draw');

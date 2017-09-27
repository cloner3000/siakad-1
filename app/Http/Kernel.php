<?php namespace Siakad\Http;
	
	use Illuminate\Foundation\Http\Kernel as HttpKernel;
	
	class Kernel extends HttpKernel {
		
		/**
			* The application's global HTTP middleware stack.
			*
			* @var array
		*/
		protected $middleware = [
		'Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode',
		'Illuminate\Cookie\Middleware\EncryptCookies',
		'Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse',
		'Illuminate\Session\Middleware\StartSession',
		'Illuminate\View\Middleware\ShareErrorsFromSession',
		'Siakad\Http\Middleware\VerifyCsrfToken',
		
		];
		
		/**
			* The application's route middleware.
			*
			* @var array
		*/
		protected $routeMiddleware = [
		'auth' => 'Siakad\Http\Middleware\Authenticate',
		'auth.basic' => 'Illuminate\Auth\Middleware\AuthenticateWithBasicAuth',
		'guest' => 'Siakad\Http\Middleware\RedirectIfAuthenticated',
		'maintenis' => 'Siakad\Http\Middleware\CheckForMaintenis',
		'roles' => 'Siakad\Http\Middleware\CheckRole',
		'kuesioner' => 'Siakad\Http\Middleware\CheckKuesioner',
		'profil' => 'Siakad\Http\Middleware\CheckProfil',
		];
		
	}

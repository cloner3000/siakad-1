<?php namespace Siakad\Http\Controllers\Auth;
	
	use Session;
	use Validator;
	use Siakad\User;
	use Illuminate\Http\Request;
	use Siakad\Http\Controllers\Controller;
	use Illuminate\Foundation\Auth\ThrottlesLogins;
	use Illuminate\Foundation\Auth\AuthenticatesUsers;
	
	class AuthController extends Controller {
		
		/*
			|--------------------------------------------------------------------------
			| Registration & Login Controller
			|--------------------------------------------------------------------------
			|
			| This controller handles the registration of new users, as well as the
			| authentication of existing users. By default, this controller uses
			| a simple trait to add these behaviors. Why don't you explore it?
			|
		*/
		
		use AuthenticatesUsers, ThrottlesLogins;
		
		protected $username = 'username';
		protected $maxLoginAttempts = 3;
		protected $lockoutTime = 300;
		/**
			* Create a new authentication controller instance.
			*
			* @param  \Illuminate\Contracts\Auth\Guard  $auth
			* @param  \Illuminate\Contracts\Auth\Registrar  $registrar
			* @return void
		*/		
		public function __construct()
		{
			$this -> middleware('guest', ['except' => 'getLogout']);
		}
		
		public function authenticated(Request $request)
		{
			$date = date('Y-m-d H:i:s');
			$ip =  $request -> ip();
			\Siakad\User::where($this->loginUsername(), '=', $request->only($this->loginUsername())) -> update(['last_login' => $date, 'last_ip' => $ip]);
			return redirect()->intended($this->redirectPath());
		} 
		
	}

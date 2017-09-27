<?php namespace Siakad\Http\Controllers;
	
	use Cache;
	use Input;
	use Session;
	use Redirect;
	use Siakad\User;
	use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	use Illuminate\Http\Request;
	
	use Siakad\Jobs\SendResetPasswordRequestEmail;
	// use Illuminate\Contracts\Auth\Authenticatable as UserAuth;
	
	class UsersController extends Controller {
		protected $rules = [
		// 'name' => ['required', 'valid_name_number', 'min:3'],
		];
		
		protected $roles = [
		'mahasiswa' => 512,
		'dosen' => 128
		];
		
		public function getUsername()
		{
			return view('auth.passwords.username');
		}
		
		public function getResetPassword($username, $reset_token)
		{
			$user = User::whereUsername($username) -> where('reset_token', $reset_token) -> first();
			if(!$user) abort(404);
			return view('auth.passwords.reset', compact('user'));
		}
		
		public function postResetPassword(Request $request)
		{
			$this->rules['password'] = array('min:3', 'same:password_confirmation');
			$this -> validate($request, $this->rules);
			
			$input = array_except(Input::all(), ['_token', 'password_confirmation']);
			
			$user = User::whereUsername($input['username']) -> where('reset_token', $input['reset_token']);
			if(!$user) abort(404);
			
			$input['password'] = bcrypt($input['password']);
			unset($input['old-password']);
			$input['reset_token'] = null;
			$input['reset_ip'] = null;
			$user -> update($input);
			return redirect('/auth/login') -> with('message', 'Password berhasil diubah.');
		}
		
		public function postUsername(Request $request)
		{
			$this->validate($request, ['username' => 'required']);
			$user = User::whereUsername(Input::get('username')) -> first();
			
			if(!$user) return view('auth.passwords.username') -> withErrors(['username' => 'Maaf, Username tidak terdaftar.']);
			
			switch($user -> authable_type)
			{
				case 'Siakad\Dosen':
				$data = \Siakad\Dosen::whereId($user -> authable_id) -> first() -> toArray();
				break;
				
				case 'Siakad\Mahasiswa':
				$data = \Siakad\Mahasiswa::whereId($user -> authable_id) -> first() -> toArray();
				break;
				
				case 'Siakad\Admin':
				$data = \Siakad\Admin::whereId($user -> authable_id) -> first() -> toArray();
				break;
			}
			
			
			if($user -> authable_type == 'Siakad\Mahasiswa')
			{
				if($data['statusMhs'] != '1') return view('auth.passwords.username') -> withErrors(['username' => 'Status Mahasiswa tidak aktif. Silahkan hubungi Administrator untuk informasi lebih lanjut.']);
			}
			
			$validator = \Validator::make($data, ['email' => 'required|email']);			
			if($validator -> fails()) return view('auth.passwords.username') -> withErrors(['username' => 'Email belum diisi / format email salah. Silahkan hubungi Administrator untuk informasi lebih lanjut.']);
			
			$data['username'] = $user -> username;
			$data['ip'] = $request -> ip();
			$data['config'] = config('custom');
			$data['reset_token'] = str_random(64);
			
			$user -> update([
			'reset_token' => $data['reset_token'],
			'reset_ip' => $data['ip']
			]);
			
			$this -> dispatch(new SendResetPasswordRequestEmail($data));
			return Redirect::route('password.username') -> with('message', 'Petunjuk reset password telah dikirimkan ke email anda.');	
		}
		
		public function cleanup($type)
		{
			//select * from users where authable_type = 'Siakad\\Mahasiswa' and authable_id NOT IN (SELECT id FROM mahasiswa);
		}
		
		public function myProfile()
		{
			$auth = \Auth::user();
			switch($auth -> authable_type)
			{
				case 'Siakad\Dosen':
				$dosen = \Siakad\Dosen::whereId($auth -> authable_id) -> first();
				return view('dosen.profil', compact('dosen'));
				break;
				
				case 'Siakad\Mahasiswa':
				$mahasiswa = \Siakad\Mahasiswa::whereId($auth -> authable_id) -> first();
				$alamat = '';
				if($mahasiswa['jalan'] != '') $alamat .= 'Jl. ' . $mahasiswa['jalan'] . ' ';
				if($mahasiswa['dusun'] != '') $alamat .= $mahasiswa['dusun'] . ' ';
				if($mahasiswa['rt'] != '') $alamat .= 'RT ' . $mahasiswa['rt'] . ' ';
				if($mahasiswa['rw'] != '') $alamat .= 'RW ' . $mahasiswa['rw'] . ' ';
				if($mahasiswa['kelurahan'] != '') $alamat .= $mahasiswa['kelurahan'] . ' ';
				if($mahasiswa['id_wil'] != '') 
				{
					$data = \Siakad\Wilayah::dataKecamatan($mahasiswa['id_wil']) -> first();
					$alamat .= trim($data -> kec) . ' ' . trim($data -> kab) . ' ' . trim($data -> prov) . ' ';
				}
				
				if($mahasiswa['kodePos'] != '') $alamat .= $mahasiswa['kodePos'];
				return view('mahasiswa.profil', compact('mahasiswa', 'alamat'));
				break;
				
				default:
				$user = $auth;
				return view('users.admin.show', compact('user'));
			}
		}
		public function myProfileEdit()
		{
			$auth = \Auth::user();
			switch($auth -> authable_type)
			{
				case 'Siakad\Dosen':
				$dosen = \Siakad\Dosen::whereId($auth -> authable_id) -> first();
				return view('dosen.profiledit', compact('dosen'));
				break;
				
				case 'Siakad\Mahasiswa':
				$mahasiswa = \Siakad\Mahasiswa::whereId($auth -> authable_id) -> first();
				$negara = Cache::get('negara', function() {
					$negara = \Siakad\Negara::orderBy('nama') -> lists('nama', 'kode');
					Cache::put('negara', $negara, 60);
					return $negara;
				});
				$wilayah = Cache::get('wilayah', function() {
					$wilayah = \Siakad\Wilayah::kecamatan() -> get();
					$tmp[1] = '';
					foreach($wilayah as $kec)
					{
						$tmp[$kec -> id_wil] = $kec['kec'] . ' - ' . $kec['kab'] . ' - ' . $kec['prov'];
					}
					Cache::put('wilayah', $tmp, 60);
					return $tmp;
				});
				return view('mahasiswa.profiledit', compact('mahasiswa', 'wilayah', 'negara'));
				break;
				
				default:
				$user = $auth;
				return view('users.admin.edit', compact('user'));
			}
		}
		public function myProfileUpdate(Request $request)
		{
			switch(\Auth::user() -> authable_type)
			{
				case 'Siakad\Dosen':
				$input = array_except(Input::all(), '_method');
				$dosen = \Siakad\Dosen::find(\Auth::user() -> authable_id);
				$dosen-> update($input);
				break;
				
				case 'Siakad\Mahasiswa':
				$rules = [
				'tmpLahir' => ['required', 'valid_name'],
				'tglLahir' => ['required', 'date', 'date_format:d-m-Y'],
				'jenisKelamin' => ['required'],
				'NIK' => ['required', 'numeric'],
				'kelurahan' => ['required'],
				'hp' => ['required'],
				'id_wil' => ['required', 'numeric'],
				'namaIbu' => ['required', 'valid_name'],
				'namaAyah' => ['required', 'valid_name'],
				];
				$this -> validate($request, $rules);
				$input = array_except(Input::all(), '_method');
				$mahasiswa = \Siakad\Mahasiswa::find(\Auth::user() -> authable_id);
				$mahasiswa -> update($input);
				break;
				
				
			}
			return Redirect::route('user.profile') -> with('message', 'Profil berhasil diperbarui.');
		}
		
		/**
			* Query building for search
		**/
		/* 		public function preSearch()
			{
			$q = strtolower(Input::get('q'));
			$qclean = preg_replace("[^ 0-9a-zA-Z]", " ", $q);
			dd($qclean);
			while (strstr($qclean, "  ")) {
			$qclean = str_replace("  ", " ", $qclean);
			}
			
			$qclean = str_replace(" ", "_", $qclean);
			
			if ($q != '') {
			return redirect( '/users/search/'.$qclean );
			}
		} */
		
		/**
			* Search
		**/
		/* 		public function search($query)
			{			
			$subtitle = '';
			$search_field = true;
			
			$query =str_replace('_', ' ', $query);
			$users = User::search($query) -> paginate(20);
			$message = 'Ditemukan ' . $users -> total() . ' hasil pencarian';
			return view('users.index', compact('message', 'users', 'subtitle', 'search_field'));
		} */
		/**
			* Display a listing of the resource.
			*
			* @return Response
		*/
		public function index()
		{
			$input = Input::all();	
			$q = Input::get('q');
			$filter = Input::get('filter', 'all');
			$users = User::leftJoin('roles', 'users.role_id', '=', 'roles.id');
			if(isset($q) and $q != '') $users = $users -> orWhere('username', 'like', '%' . $q . '%');
			
			$subtitle = '';
			switch($filter)
			{
				case 'struktural':
				// $users = $users -> leftJoin('admin', 'admin.id', '=', 'users.authable_id') -> whereIn('role_id', [2, 4096, 4, 8, 16, 257, 256, 64, 65]) -> orderBy('roles.sort');
				$users = $users -> leftJoin('admin', 'admin.id', '=', 'users.authable_id') 
				-> whereNotIn('role_id', [1, 32, 128, 512, 1024, 2048]) -> orderBy('roles.sort');
				$users = $users -> select('admin.nama', 'users.username', 'users.id AS user_id', 'roles.sub', 'roles.name AS role_name');
				$subtitle = 'Struktural';
				break;
				
				case 'dosen':
				$users = $users -> leftJoin('dosen', 'dosen.id', '=', 'users.authable_id') -> where('role_id', 128);
				if(isset($q) and $q != '') $users = $users -> orWhere('dosen.nama', 'like', '%' . $q . '%');
				$users = $users -> select('dosen.*', 'users.username', 'users.id AS user_id', 'roles.sub', 'roles.name AS role_name');
				$subtitle = 'Dosen';
				break;
				
				// case 'mahasiswa':
				default:
				$users = $users -> leftJoin('mahasiswa', 'mahasiswa.id', '=', 'users.authable_id') -> leftJoin('prodi', 'mahasiswa.prodi_id', '=', 'prodi.id')  -> join('kelas', 'mahasiswa.kelasMhs', '=', 'kelas.id') -> where('role_id', 512);
				if(isset($q) and $q != '') $users = $users -> orWhere('mahasiswa.nama', 'like', '%' . $q . '%');
				$users = $users -> select('prodi.nama AS prodi', 'mahasiswa.nama', 'mahasiswa.NIM', 'users.username', 'users.id AS user_id', 'roles.sub', 'roles.name AS role_name', 'kelas.nama AS kelas');
				$subtitle = 'Mahasiswa';
				break;
			}			
			$users = $users -> paginate(40);
			
			return view('users.index', compact('users', 'subtitle'));
		}
		
		/**
			* Show the form for creating a new resource.
			*
			* @return Response
		*/
		public function create()
		{
			$roles0 = \Siakad\Role::whereNotIn('id', [1, 32, 128, 512, 1024, 2048]) ->get();
			foreach($roles0 as $role)
			{
				$roles[$role -> id] = $role -> name . ' ' . $role -> sub;
			}
			return view('users.create', compact('roles'));
		}
		
		/**
			* Store a newly created resource in storage.
			*
			* @param \Illuminate\Http\Request $request
			* @return Response
			*
		*/
		public function store(Request $request)
		{
			$this->rules['username'] = ['required', 'alpha_num', 'min:3', 'unique:users'];
			$this->rules['password'] = ['required', 'min:3', 'same:password_confirmation'];
			$this -> validate($request, $this->rules);
			
			$input = array_except(Input::all(), ['_token', 'password_confirmation']);
			$input['password'] = bcrypt($input['password']);
			
			$authable = array_only($input, ['nama', 'telp', 'email', 'foto']);
			$admin = \Siakad\Admin::create($authable);
			
			$authinfo = array_except($input, ['nama', 'telp', 'email', 'foto']);
			$authinfo['authable_id'] = $admin -> id;
			$authinfo[ 'authable_type'] = 'Siakad\Admin';
			User::create($authinfo);
			
			return Redirect::route('pengguna.index') -> with('message', 'Data pengguna telah disimpan');
		}
		
		/**
			* Display the specified resource.
			*
			* @param  int  $id
			* @return Response
		*/
		public function show($id)
		{
			$user = User::find($id);
			return view('users.show', compact('user'));
		}
		
		/**
			* Show the form for editing the specified resource.
			*
			* @param  int  $id
			* @return Response
		*/
		public function edit($id)
		{
			$user = User::find($id);
			if($user -> role_id == 512 or $user -> role_id == 128) // dont change role is user is Mahasiswa
			{
				$roles = null;
			}
			else
			{
				$tmp = \Siakad\Role::whereNotIn('id', [1, 32, 128, 512, 1024, 2048]) ->get();
				foreach($tmp as $role)
				{
					$roles[$role -> id] = $role -> name . ' ' . $role -> sub;
				}
			}
			
			return view('users.edit', compact('user', 'roles'));
		}
		
		public function changePassword()
		{
			$user = \Auth::user();
			return view('users.changepassword', compact('user'));
		}
		public function updatePassword($user_id, Request $request)
		{
			$this->rules['password'] = array('min:3', 'same:password_confirmation');
			$this -> validate($request, $this->rules);
			
			$input = array_except(Input::all(), ['_token', 'password_confirmation']);
			$user = User::find($user_id);
			
			if(\Hash::check($input['old-password'], $user -> password)) 
			{
				$input['password'] = bcrypt($input['password']);
				unset($input['old-password']);
				$user -> update($input);
				
				return Redirect::route('password.change') -> with('message', 'Password berhasi diubah.');
			}
			return Redirect::route('password.change') -> withErrors('Password sekarang salah, mohon periksa kembali.');
		}
		
		//reset password mahasiswa
		//25 09 2016
		//reset password
		//20 10  2016
		
		public function cariPengguna()
		{
			$input = Input::all();
			$results = \DB::select('
			SELECT username, nama 
			FROM users 
			INNER JOIN mahasiswa ON users.authable_id = mahasiswa.id 
		WHERE mahasiswa.nama LIKE :nama OR mahasiswa.NIM LIKE :nim 
		ORDER BY mahasiswa.NIM', 
		['nama' => '%' . $input['query'] . '%', 'nim' => '%' . $input['query'] . '%']
		);
		
		return \Response::json(['results' => $results]);
		}
		
		public function resetPassword($target,  $filter=null)
		{
		if($target == 'mahasiswa')
		{
		if($filter != null)
		{
		return view('users.resetpasswordmahasiswa2');
		}
		else
		{
		return view('users.resetpasswordmahasiswa');					
		}
		}
		elseif($target == 'dosen')
		{
		abort(404);		
		// return view('users.resetpassworddosen');		
		}
		}
		public function resetPasswordProses($target, $filter=null)
		{
		$tmp = null;
		$input = Input::except(['_token']);
		if($target == 'mahasiswa')
		{
		if($filter == null)
		{
		$users = User::where('role_id', $this -> roles[$target]);
		if($users -> count())
		{
		$plain_password = isset($input['password']) && $input['password'] != '' ? $input['password'] : str_random();
		$password = bcrypt($plain_password);
		$users -> update(['password' => $password]);
		
		return redirect('/pengguna/resetpassword/'. $target) -> with('success', 'Password seluruh Mahasiswa berhasil diubah menjadi "' . $plain_password . '".');
		}
		}
		else
		{
		$input['target'] = json_decode($input['target'], true);
		if(count($input['target']) > 0)
		{
		if($input['options'] == 'random-all')
		{
		$users = User::whereIn('username', $input['target']);
		if($users -> count())
		{
		$plain_password = str_random(6);
		
		foreach($input['target'] as $target) $tmp[$target] = $plain_password;
		$cache_name = md5(date('Y-m-d H:i:s'));
		\Cache::put($cache_name, $tmp, 60);
		
		$password = bcrypt($plain_password);
		$users -> update(['password' => $password]);
		
		return redirect('/pengguna/resetpassword/mahasiswa/filter') -> with('success_raw', 'Password Mahasiswa berhasil diubah. <a target="_blank" href="'. url('/pengguna/cetakpassword?key=' . $cache_name) .'" class="btn btn-danger btn-xs btn-flat"><i class="fa fa-print"></i> Cetak kartu</a>');
		}
		}
		elseif($input['options'] == 'text')
		{
		if($input['textPassword'] != '')
		{
		$users = User::whereIn('username', $input['target']);
		if($users -> count())
		{
		$plain_password = $input['textPassword'];
		
		foreach($input['target'] as $target) $tmp[$target] = $plain_password;
		$cache_name = md5(date('Y-m-d H:i:s'));
		\Cache::put($cache_name, $tmp, 60);
		
		$password = bcrypt($plain_password);
		$users -> update(['password' => $password]);
		
		return redirect('/pengguna/resetpassword/mahasiswa/filter') -> with('success_raw', 'Password Mahasiswa berhasil diubah. <a target="_blank" href="'. url('/pengguna/cetakpassword?key=' . $cache_name) .'" class="btn btn-danger btn-xs btn-flat"><i class="fa fa-print"></i> Cetak kartu</a>');
		}
		}
		else
		{
		return back() -> withErrors(['text_not_set' => 'Password harus diisi']);			
		}
		}
		elseif($input['options'] == 'random')
		{
		foreach($input['target'] as $target)
		{
		$plain_password = str_random(6);
		$tmp[$target] = $plain_password;
		User::whereUsername($target) -> update(['password' => bcrypt($plain_password)]);
		}
		$cache_name = md5(date('Y-m-d H:i:s'));
		\Cache::put($cache_name, $tmp, 60);
		return redirect('/pengguna/resetpassword/mahasiswa/filter') -> with('success_raw', 'Password Mahasiswa berhasil diubah. <a target="_blank" href="'. url('/pengguna/cetakpassword?key=' . $cache_name) .'" class="btn btn-danger btn-xs btn-flat"><i class="fa fa-print"></i> Cetak kartu</a>');
		}
		}
		}
		}
		// return redirect('/pengguna/resetpassword/'. $target)  -> withErrors(['role_not_found' => 'Data pengguna tidak ditemukan.']);			
		return back()  -> withErrors(['role_not_found' => 'Data pengguna tidak ditemukan.']);			
		}
		
		public function printPassword()
		{
		$tmp = \Cache::get(Input::get('key'));
		$users = User::whereIn('username', array_keys($tmp)) -> get();
		// dd($users);
		if($tmp == null) abort(404);
		return view('users.printpassword', compact('tmp', 'users'));
		}
		
		/**
		* Update the specified resource in storage.
		*
		* @param  int  $id
		* @return Response
		*/
		public function update(Request $request, $id)
		{
		$this->rules['password'] = array('min:3', 'same:password_confirmation');
		$this -> validate($request, $this->rules);
		
		$user = User::find($id);
		$input = Input::except('_token', 'password_confirmation');
		
		if($input['password'] != '' && $input['password'] != null) 
		{
		$input['password'] = bcrypt($input['password']);
		}
		else
		{
		unset($input['password']);	
		}
		
		if($input['foto'] != '')	$authable = array_only($input, ['nama', 'telp', 'email', 'foto']);
		else $authable = array_only($input, ['nama', 'telp', 'email']);
		$authinfo = array_except($input, ['nama', 'telp', 'email', 'foto']);
		
		$user -> authable() -> update($authable);
		$user -> update($authinfo);
		
		return Redirect::route('pengguna.index') -> with('message', 'Data pengguna berhasil diperbarui.');
		}
		
		/**
		* Remove the specified resource from storage.
		*
		* @param  int  $id
		* @return Response
		*/
		public function destroy($id)
		{
		User::find($id) -> delete();
		return redirect() -> back() -> with('message', 'Data Pengguna berhasil dihapus.');
		}
		}
				
<?php
	
	namespace Siakad\Http\Controllers;
	
	use Illuminate\Support\Facades\Input;
	use Redirect;
	
	use Siakad\Dosen;
	use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	use Illuminate\Http\Request;
	
	class DosenController extends Controller
	{		
		protected $rules = [
		'NIP' => ['digits:18'],		
		'NIDN' => ['digits:10'],		
		'NIK' => ['required', 'digits_between:16,17'],		
		];
		
		//jurnal
		public function jurnal($dosen_id)
		{
			$dosen = Dosen::find($dosen_id);
			$jurnal = \Siakad\DosenJurnal::jurnal($dosen_id) -> get();
			return view('dosen.jurnal.detail', compact('jurnal', 'dosen'));
		}
		
		//aktifitas mengajar
		public function aktifitasMengajarDosen($dosen_id)
		{
			$dosen = Dosen::find($dosen_id);
			$data = \Siakad\MatkulTapel::mataKuliahDosen($dosen_id) -> with('mahasiswa') -> orderBy('tapel.nama', 'desc') -> get();
			return view('dosen.aktifitasmengajar', compact('data', 'dosen'));
		}
		
		public function gaji()
		{
			$dosen = Dosen::orderBy('kode') -> paginate(20);
			return view('dosen.gaji', compact('dosen'));
		}
		/**
			* Query building for search
		**/
		public function preSearch()
		{
			$q = strtolower(Input::get('q'));
			$qclean = preg_replace("[^ 0-9a-zA-Z]", " ", $q);
			
			while (strstr($qclean, "  ")) {
				$qclean = str_replace("  ", " ", $qclean);
			}
			
			$qclean = str_replace(" ", "_", $qclean);
			
			if ($q != '') {
				return redirect( '/dosen/search/'.$qclean );
			}
			return Redirect::back() -> withErrors(['q' => 'Isi kata kunci pencarian yang diinginkan terlebih dahulu']);
		}
		
		/**
			* Search
		**/
		public function search($query)
		{			
			
			$public = (\Auth::user() -> role_id == 1 or  \Auth::user() -> role_id == 2) ? false : true;
			$query =str_replace('_', ' ', $query);
			$dosen = Dosen::search($query)->orderBy('kode') ->paginate(20);
			
			$message = 'Ditemukan ' . $dosen -> total() . ' hasil pencarian';
			return view('dosen.index', compact('message', 'dosen', 'public'));
		}
		
		/**
			* Display a listing of the resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function index()
		{
			$public = (\Auth::user() -> role_id == 1 or  \Auth::user() -> role_id == 2) ? false : true;
			$dosen = Dosen::where('id', '>', 0) -> orderBy('kode') -> paginate(20);
			return view('dosen.index', compact('dosen', 'public'));
		}
		
		/**
			* Show the form for creating a new resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function create()
		{
			return view('dosen.create');
		}
		
		/**
			* Store a newly created resource in storage.
			*
			* @param  \Illuminate\Http\Request  $request
			* @return \Illuminate\Http\Response
		*/
		public function store(Request $request)
		{			
			$this -> validate($request, $this -> rules);
			$all = Input::all();
			$input = array_except($all, ['username', 'password']);
			
			$authinfo['username'] = (isset($all['username']) AND $all['username'] != '') ? $all['username'] : str_random(6);
			if(\Siakad\User::where('username', $authinfo['username']) -> exists()) return redirect() -> back() -> withInput() -> with('message', 'Username: ' . $authinfo['username'] . ' sudah terdaftar, harap gunakan username yang lain');
			
			$dosen = Dosen::create($input);
			
			$password = (isset($all['password']) AND $all['password'] != '') ? $all['password'] : str_random(6);
			$authinfo['password'] = bcrypt($password);
			$authinfo['role_id'] = 128;
			$authinfo['authable_id'] = $dosen -> id;
			$authinfo['authable_type'] = 'Siakad\Dosen';
			
			\Siakad\User::create($authinfo);
			
			return Redirect::route('dosen.index') -> with('message', 'Data Dosen berhasil dimasukkan. Username: ' . $authinfo['username'] . ' Password: ' . $password);
		}
		
		/**
			* Display the specified resource.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function show($id)
		{
			$dosen = Dosen::find($id);
			return view('dosen.show', compact('dosen'));
		}
		
		/**
			* Show the form for editing the specified resource.
			*
			* @param $id
			* @return \Illuminate\Http\Response
		*/
		public function edit($id)
		{
			$dosen = Dosen::find($id);
			$hasAccount = \Siakad\User::where('authable_id', $dosen ->id) -> where('authable_type', 'Siakad\\Dosen') -> exists();
			return view('dosen.edit', compact('dosen', 'hasAccount'));
		}
		
		/**
			* Update the specified resource in storage.
			*
			* @param  \Illuminate\Http\Request  $request
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function update(Request $request, $id)
		{		
			$this -> validate($request, $this -> rules);
			$all = Input::all();
			$input = array_except($all, ['_method', 'username', 'password']);
			
			$dosen = Dosen::find($id);
			if((isset($all['username']) and $all['username'] != '') and (isset($all['password']) and $all['password'] != ''))
			{
				$authinfo['username'] = $all['username'];
				$authinfo['password'] = bcrypt($all['password']);
				$authinfo['role_id'] = 128;
				$authinfo['authable_id'] = $dosen -> id;
				$authinfo['authable_type'] = 'Siakad\Dosen';
				\Siakad\User::create($authinfo);
			}
			
			$dosen-> update($input);
			
			return Redirect::route('dosen.index') -> with('message', 'Data Dosen berhasil diperbarui.');
		}
	
	/**
	* Remove the specified resource from storage.
	*
	* @param  int  $id
	* @return \Illuminate\Http\Response
	*/
	public function delete($id)
	{
	$dosen = Dosen::find($id);
	$dosen -> delete();
	return Redirect::route('dosen.index') -> with('message', 'Data Dosen berhasil dihapus.');
	}
	}
		
<?php
	
	namespace Siakad\Http\Controllers;
	
	use Cache;
	use Redirect;
	
	use Siakad\Mahasiswa;
	use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Input;
	
	class MahasiswaController extends Controller
	{
		use \Siakad\MahasiswaTrait;		
		
		protected $rules = [
		'nama' => ['required', 'valid_name'],
		'tmpLahir' => ['required', 'valid_name'],
		'tglLahir' => ['required', 'date', 'date_format:d-m-Y'],
		'jenisKelamin' => ['required'],
		'NIK' => ['required', 'digits_between:16,17'],
		'agama' => ['required'],
		'wargaNegara' => ['required', 'alpha'],
		'jenisPendaftaran' => ['required', 'numeric'],
		'tapelMasuk' => ['required', 'numeric'],
		'kelurahan' => ['required'],
		'id_wil' => ['required', 'numeric'],
		'kps' => ['required'],
		'namaIbu' => ['required', 'valid_name'],
		];
		
		public function printTranskrip($id)
		{
			$data = \Siakad\Nilai::transkrip($id) -> get();
			$mahasiswa = Mahasiswa::find($id);
			return view('mahasiswa.printtranskrip', compact('data', 'mahasiswa'));
		}
		
		public function transkrip($id)
		{
			$data = \Siakad\Nilai::transkrip($id) -> get();
			$mahasiswa = Mahasiswa::find($id);
			return view('mahasiswa.transkrip', compact('data', 'mahasiswa'));
		}
		
		public function adminFundingTypeForm()
		{
			$angkatan = $this -> getGolongan('angkatan');
			$semester = $this -> getGolongan('semester');
			$kelas = $this -> getGolongan('program');
			$jenis = config('custom.pilihan.jenisPembayaran');
			$mahasiswa = Mahasiswa::where('statusMhs', '1') -> orderBy('NIM') -> get();
			return view('mahasiswa.adminpembiayaan', compact('angkatan', 'semester', 'kelas','jenis', 'mahasiswa'));
		}
		
		public function adminFundingTypeMember()
		{
			$input = Input::all();
			$mahasiswa = Mahasiswa::where('statusMhs', '1') -> where('jenisPembayaran', $input['jenis']) -> orderBy('NIM') -> get();;
			return \Response::json(['mahasiswa' => $mahasiswa]);
		}
		public function adminFundingTypeUpdate()
		{
			$input = Input::all();
			$response = [];
			
			$result = Mahasiswa::whereIn('id', $input['id']) -> update(['jenisPembayaran' => $input['jenis']]);
			if(!$result) return \Response::json([]);
			
			$from = Mahasiswa::where('statusMhs', '1');
			if($input['angkatan-from'] !== '-') $from = $from -> where('angkatan', $input['angkatan-from']);
			if($input['semester-from'] !== '-') $from = $from -> where('semesterMhs', $input['semester-from']);
			if($input['kelas-from'] !== '-') $from = $from -> where('kelasMhs', $input['kelas-from']);
			$from = $from -> orderBy('NIM') -> get();
			
			$to = Mahasiswa::where('statusMhs', '1') -> where('jenisPembayaran', $input['jenis']) -> orderBy('NIM') -> get();
			
			return \Response::json(['to' => $to, 'from' => $from]);
		}
		
		// Status
		public function adminStatusAnggota()
		{
			$input = Input::all();
			$mahasiswa = Mahasiswa::where('statusMhs', $input['status']) -> orderBy('NIM') -> get();;
			return \Response::json(['mahasiswa' => $mahasiswa]);
		}
		
		public function adminStatus()
		{
			$angkatan = $this -> getGolongan('angkatan');
			$semester = $this -> getGolongan('semester');
			$kelas = $this -> getGolongan('program');
			foreach(config('custom.pilihan.statusMhs') as $k => $v) $status[$k] = $v;
			
			$mahasiswa = Mahasiswa::where('statusMhs', '1') -> orderBy('NIM') -> get();
			return view('mahasiswa.adminstatus', compact('angkatan', 'semester', 'kelas','status', 'mahasiswa'));
		}
		
		public function adminStatusUpdate()
		{
			$input = Input::all();
			$response = [];
			
			$result = Mahasiswa::whereIn('id', $input['id']) -> update(['statusMhs' => $input['status']]);
			if(!$result) return \Response::json([]);
			
			$from = Mahasiswa::where('id', '>', 0);
			if($input['angkatan-from'] !== '-') $from = $from -> where('angkatan', $input['angkatan-from']);
			if($input['semester-from'] !== '-') $from = $from -> where('semesterMhs', $input['semester-from']);
			if($input['kelas-from'] !== '-') $from = $from -> where('kelasMhs', $input['kelas-from']);
			$from = $from -> orderBy('NIM') -> get();
			
			$to = Mahasiswa::where('statusMhs', $input['status']) -> orderBy('NIM') -> get();
			
			return \Response::json(['to' => $to, 'from' => $from]);
		}
		
		// Perwalian
		public function adminCustodianAnggota()
		{
			$input = Input::all();
			$mahasiswa = Mahasiswa::where('statusMhs', '1') -> where('dosen_wali', $input['dosen']) -> orderBy('NIM') -> get();;
			return \Response::json(['mahasiswa' => $mahasiswa]);
		}
		
		public function adminCustodian()
		{
			$angkatan = $this -> getGolongan('angkatan');
			$semester = $this -> getGolongan('semester');
			$kelas = $this -> getGolongan('program');
			$dosen = \Siakad\Dosen::orderBy('nama') -> lists('nama', 'id');
			$mahasiswa = Mahasiswa::where('statusMhs', '1') -> orderBy('NIM') -> get();
			return view('mahasiswa.adminperwalian', compact('angkatan', 'semester', 'kelas','dosen', 'mahasiswa'));
		}
		
		public function adminCustodianUpdate()
		{
			$input = Input::all();
			$response = [];
			
			$result = Mahasiswa::whereIn('id', $input['id']) -> update(['dosen_wali' => $input['dosen']]);
			if(!$result) return \Response::json([]);
			
			$from = Mahasiswa::where('statusMhs', '1');
			if($input['angkatan-from'] !== '-') $from = $from -> where('angkatan', $input['angkatan-from']);
			if($input['semester-from'] !== '-') $from = $from -> where('semesterMhs', $input['semester-from']);
			if($input['kelas-from'] !== '-') $from = $from -> where('kelasMhs', $input['kelas-from']);
			$from = $from -> orderBy('NIM') -> get();
			
			$to = Mahasiswa::where('statusMhs', '1') -> where('dosen_wali', $input['dosen']) -> orderBy('NIM') -> get();
			
			return \Response::json(['to' => $to, 'from' => $from]);
		}
		
		public function custodian()
		{
			$mahasiswa = \Siakad\Dosen::getMahasiswa(\Auth::user() -> authable_id) -> paginate(30);
			return view('dosen.perwalian', compact('mahasiswa'));
		}
		
		public function transfer()
		{
			$angkatan = $this -> getGolongan('angkatan');
			$semester = $this -> getGolongan('semester');
			$kelas = $this -> getGolongan('program');
			
			$mahasiswa = Mahasiswa::orderBy('NIM') -> get();
			return view('mahasiswa.transfer', compact('angkatan', 'semester', 'kelas', 'mahasiswa'));
		}
		
		public function filterMahasiswa()
		{
			$input = Input::all();
			$mahasiswa = Mahasiswa::where('statusMhs', '1');
			
			if($input['angkatan'] !== '-') $mahasiswa = $mahasiswa -> where('angkatan', $input['angkatan']);
			if($input['semester'] !== '-') $mahasiswa = $mahasiswa -> where('semesterMhs', $input['semester']);
			if($input['kelas'] !== '-') $mahasiswa = $mahasiswa -> where('kelasMhs', $input['kelas']);
			$mahasiswa = $mahasiswa -> orderBy('NIM', 'desc') -> get();
			return \Response::json(['mahasiswa' => $mahasiswa]);
		}
		
		public function doTransfer()
		{
			$input = Input::all();
			$response = $mod = [];
			if($input['semester'] == '-' and $input['kelas'] == '-') return \Response::json([]);
			if($input['semester'] !== '-') $mod['semesterMhs'] = $input['semester'];
			if($input['kelas'] !== '-') $mod['kelasMhs'] = $input['kelas'];
			
			$result = Mahasiswa::whereIn('id', $input['id']) -> update($mod);
			if(!$result) return \Response::json([]);
			
			$from = Mahasiswa::where('statusMhs', '1');
			if($input['angkatan-from'] !== '-') $from = $from -> where('angkatan', $input['angkatan-from']);
			if($input['semester-from'] !== '-') $from = $from -> where('semesterMhs', $input['semester-from']);
			if($input['kelas-from'] !== '-') $from = $from -> where('kelasMhs', $input['kelas-from']);
			$from = $from -> orderBy('NIM') -> get();
			
			$to = Mahasiswa::where('statusMhs', '1');
			if($input['semester'] !== '-') $to = $to -> where('semesterMhs', $input['semester']);
			if($input['kelas'] !== '-') $to = $to -> where('kelasMhs', $input['kelas']);
			$to = $to -> orderBy('NIM') -> get();
			
			return \Response::json(['to' => $to, 'from' => $from]);
		}
		
		/* http://php.net/manual/en/function.base-convert.php#92960 */
		function romanic_number($integer, $upcase = true) 
		{ 
			$table = array('M'=>1000, 'CM'=>900, 'D'=>500, 'CD'=>400, 'C'=>100, 'XC'=>90, 'L'=>50, 'XL'=>40, 'X'=>10, 'IX'=>9, 'V'=>5, 'IV'=>4, 'I'=>1); 
			$return = ''; 
			while($integer > 0) 
			{ 
				foreach($table as $rom=>$arb) 
				{ 
					if($integer >= $arb) 
					{ 
						$integer -= $arb; 
						$return .= $rom; 
						break; 
					} 
				} 
			} 
			
			return $return; 
		} 
		
		public function printMyKhs($ta = null)
		{
			$data = $this->khs(\Auth::user() -> authable -> NIM, $ta);
			if($ta === null) return view('mahasiswa.printall', $data);
			return view('mahasiswa.print', $data);
		}
		public function viewMyKhs($ta = null)
		{
			$data = $this->khs(\Auth::user() -> authable -> NIM, $ta);
			return view('mahasiswa.khs', $data);
		}
		
		public function cetakKhs($nim, $ta = null)
		{
			$data = $this->khs($nim, $ta);
			if($ta == null) return view('mahasiswa.printall', $data);
			return view('mahasiswa.print', $data);
		}
		
		public function viewKhs($nim, $ta = null)
		{
			$data = $this->khs($nim, $ta);
			return view('mahasiswa.khs', $data);
		}
		
		public function khs($nim, $ta = null)
		{
			$all = true;
			if($ta != null) 
			{
				$all = false;
			}
			
			$mhs = Mahasiswa::with('prodi') -> with('kelas') -> where('NIM', $nim) -> first();
			$data = \Siakad\Nilai::dataKHS($nim, $ta) -> get();
			if(count($data) < 1) $nilai = [];
			else
			{
				foreach($data as $d) 
				{
					$nilai[$d -> smt][] = [
					'ta' => $d -> nama,
					'taid' => $d -> id,
					'kode' => $d -> kode,
					'kelas' => $d -> kelas2,
					'matkul' => $d -> matkul,
					'nilai' => $d -> nilai,
					'sks' => $d -> sks
					];
					
				}
				ksort($nilai);
			}
			return compact('mhs', 'nilai', 'all');
		}
		
		public function angkatan($docheck = null)
		{
			$input = Input::All();
			if($docheck == null) // ANGGOTA KELAS KULIAH -> NILAI
			{
				$where = '';
				$data = ['id' => $input['id'], 'prodi_id' => $input['prodi_id'], 'semester_matkul' => $input['semester']];
				if($input['tahun'] != '-') 
				{
					$where = '`angkatan` = :angkatan AND';
					$data['angkatan'] = $input['tahun'];
				}
				$mahasiswa = \DB::select('
				SELECT `mahasiswa`.* 
				FROM `mahasiswa` 
				WHERE ' . $where . ' `mahasiswa`.`id` NOT IN (
				SELECT `mahasiswa_id`
				FROM `nilai`
				WHERE `nilai`.`matkul_tapel_id` = :id
				) 
				AND `mahasiswa`.`prodi_id` = :prodi_id 	
				AND `mahasiswa`.`semesterMhs` = :semester_matkul 	
				ORDER BY `NIM` ASC
				', $data);
			}
			else // MAHASISWA -> SEMESTER
			{
				$mahasiswa = Mahasiswa::where('angkatan', $input['tahun']) -> orderBy('NIM') -> get();
			}
			return \Response::json(['mahasiswa' => $mahasiswa]);
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
				return redirect( '/mahasiswa/search/'.$qclean );
			}
			return Redirect::back() -> withErrors(['q' => 'Isi kata kunci pencarian yang diinginkan terlebih dahulu']);
		}
		
		/**
			* Search
		**/
		public function search()
		{			
			$aktif = \Siakad\Tapel::whereAktif('y') -> pluck('id');
			
			$query = Input::get('q');
			
			$semester = $this -> getGolongan('semester');			
			$status = $this -> getGolongan('status');
			$prodi = $this -> getGolongan('prodi');
			$program = $this -> getGolongan('program');
			$semester = $this -> getGolongan('semester');
			$angkatan = $this -> getGolongan('angkatan');
			
			$mahasiswa = Mahasiswa::with('prodi') -> with('kelas') -> with('authInfo') -> with('krs') -> search($query) -> paginate(50);
			// $mahasiswa = Mahasiswa::DaftarMahasiswa() -> search($query) -> paginate(50);
			$message = 'Ditemukan ' . $mahasiswa -> total() . ' hasil pencarian';
			return view('mahasiswa.index', compact('message', 'mahasiswa', 'semester', 'status', 'prodi', 'program', 'angkatan', 'aktif'));
		}
		
		//filter on Daftar mahasiswa
		public function filter()
		{
			$input = Input::all();
			$aktif = \Siakad\Tapel::whereAktif('y') -> pluck('id');
			
			$mahasiswa = Mahasiswa::with('prodi') -> with('kelas') -> with('authInfo') -> with('krs') -> where('NIM', '<>', '');
			if(isset($input['angkatan']) && $input['angkatan'] !== '-') $mahasiswa = $mahasiswa -> where('angkatan', $input['angkatan']);
			if(isset($input['semester']) && $input['semester'] !== '-') $mahasiswa = $mahasiswa -> where('semesterMhs', $input['semester']);
			if(isset($input['prodi']) && $input['prodi'] !== '-') $mahasiswa = $mahasiswa -> where('prodi_id', $input['prodi']);
			if(isset($input['status']) && $input['status'] !== '-') $mahasiswa = $mahasiswa -> where('statusMhs', $input['status']);
			if(isset($input['program']) && $input['program'] !== '-') $mahasiswa = $mahasiswa -> where('kelasMhs', $input['program']);
			
			// $mahasiswa = Mahasiswa::DaftarMahasiswa($input);
			
			$perpage = intval(Input::get('perpage')) > 0 ? Input::get('perpage') : 200;
			$mahasiswa = $mahasiswa -> orderBy('NIM', 'desc') -> paginate($perpage);
			
			$status = $this -> getGolongan('status');
			$prodi = $this -> getGolongan('prodi', true, true);
			$program = $this -> getGolongan('program');
			$semester = $this -> getGolongan('semester');
			$angkatan = $this -> getGolongan('angkatan');
			
			return view('mahasiswa.index', compact('mahasiswa', 'semester', 'status', 'prodi', 'program', 'angkatan', 'aktif'));
		}
		/**
			* Display a listing of the resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function index()
		{
			$aktif = \Siakad\Tapel::whereAktif('y') -> pluck('id');
			$status = $this -> getGolongan('status');
			$prodi = $this -> getGolongan('prodi', true, true);
			$program = $this -> getGolongan('program');
			$semester = $this -> getGolongan('semester');
			$angkatan = $this -> getGolongan('angkatan');
			$mahasiswa = Mahasiswa::with('prodi') -> with('kelas') -> with('authInfo') -> with('krs') -> orderBy('NIM', 'desc') -> paginate(25);
			
			// $mahasiswa = Mahasiswa::DaftarMahasiswa() -> paginate(25);
			//dd($mahasiswa);
			return view('mahasiswa.index', compact('mahasiswa', 'semester', 'status', 'prodi', 'program', 'angkatan', 'aktif'));
		}
		
		/**
			* Show the form for creating a new resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function create()
		{
			// if(!in_array(\Auth::user() -> role_id, [1, 2, 16])) abort('401');
			$prodi = \Siakad\Prodi::lists('nama', 'id');
			$kelas = \Siakad\Kelas::lists('nama', 'id');
			$dosen = \Siakad\Dosen::orderBy('nama') -> lists('nama', 'id');
			
		$tapel = \Siakad\Tapel::orderBy('nama2', 'DESC') -> lists('nama', 'nama2');		
		
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
		
		return view('mahasiswa.create', compact('prodi', 'kelas', 'dosen', 'wilayah', 'negara', 'jenisdaftar', 'tapel'));
		}
		
		/**
		* Store a newly created resource in storage.
		*
		* @param  \Illuminate\Http\Request  $request
		* @return \Illuminate\Http\Response
		*/
		public function store(Request $request)
		{			
		$this -> rules['NIM'] = ['required', 'unique:mahasiswa,NIM'];
		$this -> validate($request, $this -> rules);
		$all = Input::all();
		$input = array_except($all, ['username', 'password']);
		
		if(!isset($input['angkatan'])) $input['angkatan'] = substr($input['NIM'], 0, 4);
		
		$tapel = \Siakad\Tapel::where('nama2', $input['tapelMasuk']) -> first();
		$input['tglMasuk'] = isset($tapel -> mulai) ? date('d-m-Y', strtotime($tapel -> mulai)) : date('d-m-Y');
		$mhs = Mahasiswa::create($input);
		
		$authinfo['username'] = (isset($all['username']) AND $all['username'] != '') ? $all['username'] : ((isset($all['NIM']) AND $all['NIM'] != '') ? $all['NIM'] : str_random(6));
		$password = (isset($all['password']) AND $all['password'] != '') ? $all['password'] : str_random(6);
		$authinfo['password'] = bcrypt($password);
		$authinfo['role_id'] = 512;
		$authinfo['authable_id'] = $mhs -> id;
		$authinfo['authable_type'] = 'Siakad\Mahasiswa';
		\Siakad\User::create($authinfo);
		
		//-------------- SENAYAN 7 MEMBER REGISTRATION ----------------//
		$gender = isset($input['jenisKelamin']) AND $input['jenisKelamin'] == 'L' ? 1 : 0;
		
		$today = date('Y-m-d');
		
		$tmp = explode('-', $input['tglLahir']);
		$birth_date = $tmp[2] . '-' . $tmp[1] . '-' . $tmp[0];
		
		$address = '';
		if($input['jalan'] != '') $address .= 'Jl. ' . $input['jalan'] . ' ';
		if($input['dusun'] != '') $address .= $input['dusun'] . ' ';
		if($input['rt'] != '') $address .= 'RT ' . $input['rt'] . ' ';
		if($input['rw'] != '') $address .= 'RW ' . $input['rw'] . ' ';
		if($input['kelurahan'] != '') $address .= $input['kelurahan'] . ' ';
		if($input['id_wil'] != '') 
		{
		$data = \Siakad\Wilayah::dataKecamatan($input['id_wil']) -> first();
		if($data) $address .= trim($data -> kec) . ' ' . trim($data -> kab) . ' ' . trim($data -> prov) . ' ';
		}
		if($input['kodePos'] != '') $address .= $input['kodePos'];
		
		$member = [
		'member_id' => $authinfo['username'],
		'member_name' => $input['nama'],
		'gender' => $gender,
		'birth_date' => $birth_date,
		'member_type_id' => 1,
		'member_address' => $address,
		'member_phone' => $input['telp'],
		'member_since_date' => $today,
		'register_date' => $today,
		'input_date' => $today,
		'last_update' => $today,
		'expire_date' => date('Y-m-d', strtotime("+4 years")),
		'mpasswd' => md5($password)
		];
		if(\Siakad\SenayanMembership::create($member))
		return Redirect::route('mahasiswa.index') 
		-> with('message', 'Data mahasiswa berhasil dimasukkan. Username: ' . $authinfo['username'] . ' Password: ' . $password)
		-> with('success', 'Pendaftaran keanggotaan perpustakaan berhasil. Username: ' . $authinfo['username'] . ' Password: ' . $password);
		//-------------- END OF SENAYAN 7 MEMBER REGISTRATION ----------------//
		
		return Redirect::route('mahasiswa.index') -> with('message', 'Data mahasiswa berhasil dimasukkan. Username: ' . $authinfo['username'] . ' Password: ' . $password);
		}
		
		/**
		* Display the specified resource.
		*
		* @param  int  $id
		* @return \Illuminate\Http\Response
		*/
		public function show($id)
		{
		$mahasiswa = Mahasiswa::with('dosenwali') -> with('wisuda') -> find($id);
		if(!$mahasiswa) abort(404);
		
		$tapel = \Siakad\Tapel::whereNama2($mahasiswa -> tapelMasuk) -> first();
		
		$alamat = '';
		if($mahasiswa['jalan'] != '') $alamat .= 'Jl. ' . $mahasiswa['jalan'] . ' ';
		if($mahasiswa['dusun'] != '') $alamat .= $mahasiswa['dusun'] . ' ';
		if($mahasiswa['rt'] != '') $alamat .= 'RT ' . $mahasiswa['rt'] . ' ';
		if($mahasiswa['rw'] != '') $alamat .= 'RW ' . $mahasiswa['rw'] . ' ';
		if($mahasiswa['kelurahan'] != '') $alamat .= $mahasiswa['kelurahan'] . ' ';
		if($mahasiswa['id_wil'] != '') 
		{
		$data = \Siakad\Wilayah::dataKecamatan($mahasiswa['id_wil']) -> first();
					if($data)
		$alamat .= trim($data -> kec) . ' ' . trim($data -> kab) . ' ' . trim($data -> prov) . ' ';
		}
		
		if($mahasiswa['kodePos'] != '') $alamat .= $mahasiswa['kodePos'];
		return view('mahasiswa.show', compact('mahasiswa', 'alamat', 'tapel'));
		}
		
		/**
		* Show the form for editing the specified resource.
		*
		* @param $id
		* @return \Illuminate\Http\Response
		*/
		public function edit($id)
		{
		$mahasiswa = Mahasiswa::find($id);
		$hasAccount = \Siakad\User::where('authable_id', $mahasiswa ->id) -> where('authable_type', 'Siakad\\Mahasiswa') -> exists();
		$prodi = \Siakad\Prodi::lists('nama', 'id');
		$kelas = \Siakad\Kelas::lists('nama', 'id');
		$dosen = \Siakad\Dosen::orderBy('nama') -> lists('nama', 'id');
		
		$tapel = \Siakad\Tapel::orderBy('nama2') -> lists('nama', 'nama2');	
		
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
		return view('mahasiswa.edit', compact('mahasiswa', 'prodi', 'hasAccount', 'kelas', 'dosen', 'wilayah', 'negara', 'tapel'));
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
		
		$mahasiswa = Mahasiswa::find($id);
		
		if((isset($all['username']) and $all['username'] != '') and (isset($all['password']) and $all['password'] != ''))
		{
		$authinfo['username'] = $all['username'];
		$authinfo['password'] = bcrypt($all['password']);
		$authinfo['role_id'] = 512;
		$authinfo['authable_id'] = $mahasiswa -> id;
		$authinfo['authable_type'] = 'Siakad\Mahasiswa';
		\Siakad\User::create($authinfo);
		}
		
		if(!isset($input['angkatan'])) $input['angkatan'] = substr($input['NIM'], 0, 4);
		
		$tapel = \Siakad\Tapel::where('nama2', $input['tapelMasuk']) -> first();
		$input['tglMasuk'] = isset($tapel -> mulai) ? date('d-m-Y', strtotime($tapel -> mulai)) : date('d-m-Y');
		$mahasiswa-> update($input);
		
		return Redirect::route('mahasiswa.index', $mahasiswa->id) -> with('message', 'Data mahasiswa berhasil diperbarui.');
		}
		
		/**
		* Remove the specified resource from storage.
		*
		* @param  int  $id
		* @return \Illuminate\Http\Response
		*/
		public function destroy($id)
		{
		$mahasiswa = Mahasiswa::find($id);
		$mahasiswa -> delete();
		
		\Siakad\User::where('username', $mahasiswa -> NIM) -> delete();
		\Siakad\SenayanMembership::where('member_id', $mahasiswa -> NIM) -> delete();
		
		return Redirect::route('mahasiswa.index') -> with('success', 'Data mahasiswa berhasil dihapus.');
		}
		}
				

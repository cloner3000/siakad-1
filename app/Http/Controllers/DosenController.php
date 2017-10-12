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
		
		//EXPORT EMIS
		public function getExport()
		{
			$format = ['xlsx' => 'Microsoft Excel'];
			$ta = \Siakad\Tapel::orderBy('nama2', 'desc') -> lists('nama', 'id');
			return view('dosen.export.emis', compact('format', 'ta'));
		}
		public function postExport()
		{
			$input = Input::all();

			if(intval($input['ta']) > 1 ) 
			{
				$tapel = \Siakad\Tapel::whereId($input['ta']) -> first();
			}
			else
			{
				$tapel = \Siakad\Tapel::whereAktif('y') -> first();
			}


			$data = \DB::select("SELECT NIP, NIDN, dosen.nama AS nama_dosen, gelar_depan, gelar_belakang, jenisKelamin, 
			tmpLahir, tglLahir, NIK, nama_ibu, pns, dk.pangkat, df.jabatan, 
			no_sk_awal, tmt_sk_awal, no_sk_terbaru, tmt_sk_terbaru, instansi, statusDosen, 
			status_keaktifan, prodi.strata, prodi.nama AS nama_prodi, matkul.nama AS nama_matkul, sks_total, jabatan_tambahan,
			dpd.jenjang, dpd.bidangStudi, dpd.tgl_ijasah, ds.tahun AS tahun_sertifikasi,
			tunjangan_profesi, besar_tunjangan_profesi, IFNULL(db.jbuku, 0) AS jbuku, 
			IFNULL(dpn.jpenelitian, 0) AS jpenelitian, IFNULL(dj.jjurnal, 0) AS jjurnal, alamat, 
			kab, provinsi
			FROM dosen
			LEFT JOIN (SELECT dosen_id, jabatan FROM dosen_fungsional order by str_to_date(tmt, '%d-%m-%Y') desc limit 1) AS df on df.dosen_id = dosen.id
			LEFT JOIN (SELECT dosen_id, pangkat FROM dosen_kepangkatan order by str_to_date(tmt, '%d-%m-%Y') desc limit 1) AS dk on dk.dosen_id = dosen.id
			LEFT JOIN (SELECT dosen_id, prodi_id FROM dosen_penugasan group by dosen_id order by str_to_date(tmt_surat_tugas, '%d-%m-%Y') desc) AS  dp on dp.dosen_id = dosen.id
			LEFT JOIN (SELECT dpd1.dosen_id, dpd1.jenjang, dpd1.bidangStudi, dpd1.tahunLulus, dpd1.tgl_ijasah
			FROM dosen_pendidikan dpd1                    
			  LEFT JOIN dosen_pendidikan dpd2              
			      ON dpd1.dosen_id = dpd2.dosen_id AND (dpd1.tahunLulus < dpd2.tahunLulus or (dpd1.tahunLulus = dpd2.tahunLulus and dpd1.id < dpd2.id))
			WHERE dpd2.tahunLulus is NULL) AS dpd 
				on dpd.dosen_id = dosen.id
			LEFT JOIN (SELECT ds1.dosen_id, ds1.tahun
			FROM dosen_sertifikasi ds1                    
			  LEFT JOIN dosen_sertifikasi ds2              
			      ON ds1.dosen_id = ds2.dosen_id AND (ds1.tahun < ds2.tahun or (ds1.tahun = ds2.tahun and ds1.id < ds2.id))
			WHERE ds2.tahun is NULL) AS  ds on ds.dosen_id = dosen.id
			LEFT JOIN (
			  SELECT dosen_id, count(judul) AS  jbuku
			  FROM dosen_buku
			  GROUP BY dosen_id
			) db ON dosen.id = db.dosen_id
			LEFT JOIN (
			  SELECT dosen_id, count(judul) AS  jpenelitian
			  FROM dosen_penelitian
			  GROUP BY dosen_id
			) dpn ON dosen.id = dpn.dosen_id
			LEFT JOIN (
			  SELECT dosen_id, count(judul_artikel) AS  jjurnal
			  FROM dosen_jurnal
			  WHERE level_jurnal = 2
			  GROUP BY dosen_id
			) dj ON dosen.id = dj.dosen_id
			LEFT JOIN prodi on prodi.id = dp.prodi_id
			LEFT JOIN matkul_tapel on matkul_tapel.dosen_id = dosen.id
			INNER JOIN tapel on tapel.id = matkul_tapel.tapel_id
			INNER JOIN kurikulum_matkul on kurikulum_matkul.id = matkul_tapel.kurikulum_matkul_id
			INNER JOIN matkul on matkul.id = kurikulum_matkul.matkul_id
			WHERE tapel.id=". $tapel -> id ."
			GROUP BY matkul.nama, prodi.id
			ORDER BY dosen.nama");

			$title = 'Form PTKI TA '. strtoupper($tapel -> nama) .'(Dosen)';
			$this -> toXlsx(str_slug($title), $title, 'dosen.export.emis_tpl', $data);
		}
		private function toXlsx($filename, $title, $tpl, $rdata)
		{
			\Excel::create($filename, function($excel) use($title, $tpl, $rdata) {
				$excel
				-> setTitle($title)
				-> setCreator('schz')
				-> setLastModifiedBy('Schz')
				-> setManager('Schz')
				-> setCompany('Schz. Co')
				-> setKeywords('Al-Hikam, STAIMA, STAIMA Al-Hikam Malang, '. $title)
				-> setDescription($title);
				$excel -> sheet('title', function($sheet) use($tpl, $rdata){
					$sheet
					-> setOrientation('landscape')
					-> setFontSize(12)
					-> loadView($tpl) -> with(compact('rdata'));
				});
				
			}) -> download('xlsx');
			return;
		}
		
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
						

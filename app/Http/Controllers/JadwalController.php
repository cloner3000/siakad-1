<?php
	
	namespace Siakad\Http\Controllers;
	
	use Redirect;
	
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Input;
	
	use Siakad\Jadwal;
	use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	class JadwalController extends Controller
	{
		public function dosen()
		{
			$data = Jadwal::JadwalKuliah('dosen') -> get();
			return view('jadwal.dosen', compact('data'));
		}
		
		public function mahasiswa()
		{
			$data = Jadwal::JadwalKuliah('mahasiswa') -> get();
			return view('jadwal.mahasiswa', compact('data'));
		}
		
		public function index()
		{
			$p = (Input::get('prodi') != '' and  Input::get('prodi') != 0) ? Input::get('prodi') : null;
			$ta_id = (Input::get('ta') != '' and  Input::get('ta') != 0) ? Input::get('ta') : null;
			$aktif = $ta_id == null ? explode(' ', \Siakad\Tapel::whereAktif('y') -> pluck('nama')) : explode(' ', \Siakad\Tapel::whereId($ta_id) -> pluck('nama'));
			
			if($aktif[1] == 'Genap') $smt = range(2, 8, 2);
			else $smt = range(1, 7, 2);
			
			//show option if admin
			if(\Auth::user() -> role_id <= 2) 
			{
				$tmp = \Siakad\Prodi::orderBy('singkatan') -> get();
				$prodi[0] = '-- Semua PRODI --';
				foreach($tmp as $t) $prodi[$t -> id] = $t -> nama;
				
				$tmp = \Siakad\Tapel::orderBy('nama2') -> get();
				$ta[0] = '-- Semua Tahun Akademik --';
				foreach($tmp as $t) $ta[$t -> id] = $t -> nama;
			}
			
			$data = Jadwal::JadwalKuliah('admin', $p, $ta_id) -> get();
			$x = null;
			if($data)
			{
				foreach($data as $j)
				{
					$jam = $j -> jam_mulai . ' - ' . $j -> jam_selesai;
					$x[$j -> nama_prodi . '|' . $j->program][$j -> hari][$jam][$j->semester] = ['matkul' => $j -> matkul, 'kelas' => $j -> kelas, 'dosen' => $j -> dosen, 'sks' => $j -> sks, 'id' => $j -> id, 'mtid' => $j -> mtid];
				}
			}
			$data = $x;
			return view('jadwal.index', compact('data', 'prodi', 'smt', 'aktif', 'ta'));
		}
		
		public function index2()
		{		
			if(\Auth::user() -> role_id <= 2) 
			{
				$tmp = \Siakad\Prodi::orderBy('singkatan') -> get();
				$prodi[0] = '-- Semua PRODI --';
				foreach($tmp as $t) $prodi[$t -> id] = $t -> nama;
				
				$tmp = \Siakad\Tapel::orderBy('nama2') -> get();
				$ta[0] = '-- Semua Tahun Akademik --';
				foreach($tmp as $t) $ta[$t -> id] = $t -> nama;
			}
			
			$p = (Input::get('prodi') != '' and  Input::get('prodi') != 0) ? Input::get('prodi') : null;
			$ta_id = (Input::get('ta') != '' and  Input::get('ta') != 0) ? Input::get('ta') : null;
			$data = Jadwal::JadwalKuliah('admin', $p, $ta_id) -> get();
			return view('jadwal.index2', compact('data', 'prodi', 'smt', 'ta'));
		}
		
		public function create()
		{
			$matkul = null;
			$prodi = \Auth::user() -> role_id > 2 ? true : false;
			$data = \Siakad\MatkulTapel::kelasKuliah($prodi) -> get();
			foreach($data as $mk) $matkul[$mk -> mtid] = $mk -> prodi .  ' - '  . $mk -> program . ' Kelas ' . $mk -> kelas . ' - ' . $mk -> kode . ' - ' . $mk -> matkul .' - ' . $mk -> dosen . ' - Smt ' . $mk -> semester . ' - '. $mk -> sks . ' SKS';
			
			$ruang = \Siakad\Ruang::lists('nama', 'id');
			return view('jadwal.create', compact('matkul', 'ruang'));	
		}
		
		private function crashCheck($data)
		{
			$crash = \DB::select('
			SELECT j.id
			FROM jadwal j
			INNER JOIN matkul_tapel mt ON mt.id = j.matkul_tapel_id
			INNER JOIN tapel tp ON tp.id = mt.tapel_id
			WHERE dosen_id = (
			SELECT dosen_id 
			FROM matkul_tapel
			WHERE matkul_tapel.id = :id
			) 
			AND tp.aktif = "y"
			AND hari = :hari 
			AND jam_mulai BETWEEN TIME(:mulai) AND TIME(:selesai)
			',
			$data);
			
			if(count($crash)) return true;
			return false;
		}
		public function store()
		{
			$input = array_except(Input::all(), '_token');
			
			if($this -> crashCheck([
			'id' => $input['matkul_tapel_id'],
			'hari' => $input['hari'],
			'mulai' => $input['jam_mulai'],
			'selesai' => $input['jam_selesai']
			])) return Redirect::back() -> with('warning', 'Jadwal bentrok, Dosen sudah mempunyai jam mengajar pada hari dan jam tersebut. Harap periksa ulang');
			
			Jadwal::create($input);
			return Redirect::route('matkul.tapel.jadwal') -> with('message', 'Jadwal berhasil disimpan');
		}
		
		public function delete($id)
		{
			Jadwal::find($id) -> delete();
			return Redirect::route('matkul.tapel.jadwal') -> with('message', 'Jadwal berhasil dihapus');
		}
		
		public function edit($id)
		{
			$jadwal = Jadwal::find($id);
			
			$prodi = \Auth::user() -> role_id > 2 ? true : false;
			$data = \Siakad\MatkulTapel::matkulAktif($prodi) -> get();
			foreach($data as $mk) $matkul[$mk -> mtid] = $mk -> kd . ' - ' . $mk -> matkul .' - ' . $mk -> prodi . ' - ' . $mk -> dosen . ' - Smt ' . $mk -> semester . ' - '. $mk -> sks . ' SKS - ' . $mk -> program . ' Kelas ' . $mk -> kelas;
			$ruang = \Siakad\Ruang::lists('nama', 'id');			
			return view('jadwal.edit', compact('jadwal', 'matkul', 'ruang'));	
		}
		
		public function update(Request $request, $id)
		{
			$input = Input::except('_token', '_method');
			
			if($this -> crashCheck([
			'id' => $input['matkul_tapel_id'],
			'hari' => $input['hari'],
			'mulai' => $input['jam_mulai'],
			'selesai' => $input['jam_selesai']
			])) return Redirect::back() -> with('warning', 'Jadwal bentrok, Dosen sudah mempunyai jam mengajar pada hari dan jam tersebut. Harap periksa ulang');
			
			
			Jadwal::find($id) -> update($input);
			return Redirect::route('matkul.tapel.jadwal') -> with('message', 'Jadwal berhasil diperbarui');
		}
	}

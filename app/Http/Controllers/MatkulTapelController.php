<?php
	
	namespace Siakad\Http\Controllers;
	
	use Illuminate\Support\Facades\Input;
	use Redirect;
	
	use Siakad\MatkulTapel;
	use Illuminate\Http\Request;
	
	use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	class MatkulTapelController extends Controller
	{
		
		use \Siakad\FileEntryTrait;
		public function showFormUploadFile($matkul_tapel_id, $tipe)
		{
			$data = MatkulTapel::getDataMataKuliah($matkul_tapel_id) -> first();
			$nama = str_slug($tipe . '-' . $data -> matkul . '-' . $data -> ta . '-' . $data -> dosen, '-');
			return view('matkul.tapel.upload', compact('matkul_tapel_id', 'tipe', 'nama'));
		}
		
		public function uploadFile()
		{
			$input = Input::all();
			$input['akses'] = ["512"]; //Mahasiswa
			switch($input['jenis'])
			{
				case 'rpp':
				$input['tipe'] = '3';
				break;
				
				case 'silabus':
				$input['tipe'] = '4';
				break;
			}
			$result = $this -> upload($input);
			
			if(!$result['success'])
			{
				return \Response::json(['success' => false, 'error' => 'File yang diperbolehkan adalah: PDF, DOC, DOCX']);
			}
			else
			{
				$saved = MatkulTapel::find($input['id']) -> update([$input['jenis'] => $result['id']]);
				if($saved)
				{ 
					return \Response::json(['success' => true, 'filename' => $result['filename']]);
				}
			}
			return \Response::json(['success' => false, 'error' => 'An error occured while trying to save data.']);		
		}
		
		public function pesertaKuliah($matkul_tapel_id)
		{
			$data = MatkulTapel::getDataMataKuliah($matkul_tapel_id) -> first();
			if($data -> dosen_id != \Auth::user() -> authable -> id) abort(401);
			$anggota = \Siakad\Nilai::getNilaiAkhirMahasiswa($matkul_tapel_id) -> get();
			
			return view('matkul.tapel.pesertakuliah', compact('data', 'anggota', 'matkul_tapel_id'));
		}
		
		public function mataKuliahDosen()
		{
			$data = MatkulTapel::mataKuliahDosen() -> with('mahasiswa') -> get();
			return view('matkul.tapel.matakuliahdosen', compact('data'));
		}
		
		public function checkNilai($matkul_tapel_id)
		{
			$results = \DB::select('SELECT DISTINCT `jenis_nilai_id` FROM `nilai` WHERE `matkul_tapel_id` = :id', ['id' => $matkul_tapel_id]);
			
			if($results)
			foreach($results as $result) $type[] = $result->jenis_nilai_id;
			else
			$type[0] = 0;
			
			return $type;
		}
		
		public function AddMhsOut($matkul_tapel_id)
		{
			$input = Input::All();
			$result = \DB::delete('DELETE FROM `nilai` WHERE `mahasiswa_id` IN (' . implode(', ', $input['id']) . ') AND `matkul_tapel_id` = ' . $matkul_tapel_id);
			\DB::delete("DELETE FROM krs_detail WHERE krs_id IN (SELECT id FROM krs WHERE mahasiswa_id IN (" . implode(', ', $input['id']) . ")) AND `matkul_tapel_id` = " . $matkul_tapel_id);
			echo $result ? 'success' : 'failed';
		}
		
		public function AddMhsIn($matkul_tapel_id)
		{
			$types = $this->checkNilai($matkul_tapel_id);
			$input = Input::All();
			
			$tapel_id = MatkulTapel::whereId($matkul_tapel_id) -> pluck('tapel_id');
			
			foreach($types as $type) foreach($input['id'] as $id) 
			{
				$data[] = '(' . $matkul_tapel_id . ', ' . $id. ', ' . $type . ')';
				
				//update KRS
				$krs = \Siakad\Krs::firstOrCreate(['mahasiswa_id' => $id, 'tapel_id' => $tapel_id]);
				\DB::insert("INSERT IGNORE INTO `krs_detail` (krs_id, matkul_tapel_id) VALUES (" . $krs -> id . ", " . $matkul_tapel_id . ")");
			}
			$result = \DB::insert('INSERT IGNORE INTO `nilai` (`matkul_tapel_id`, `mahasiswa_id`, `jenis_nilai_id`) VALUES ' . implode(', ', $data));
			
			echo $result ? 'success' : 'failed';
		}
		
		public function AddMhs($matkul_tapel_id)
		{
			$data = MatkulTapel::getDataMataKuliah($matkul_tapel_id) -> first();
			
			$angkatan_raw = \DB::select('
			SELECT DISTINCT angkatan AS `tahun` FROM `mahasiswa`
			');
			$angkatan['-'] = 'semua';
			foreach($angkatan_raw as $k => $v) $angkatan[$v->tahun] = $v->tahun;
			
			//sort by PRODI & semester automatically....
			/*
			$mahasiswa = \DB::select('
			SELECT `mahasiswa`.* 
			FROM `mahasiswa` 
			WHERE `mahasiswa`.`id` NOT IN (
			SELECT `mahasiswa_id`
			FROM `nilai`
			WHERE `nilai`.`matkul_tapel_id` = :id
			AND `nilai`.`jenis_nilai_id` = 0
			) 
			AND `mahasiswa`.`prodi_id` = :prodi_id 			
			AND `mahasiswa`.`kelasMhs` = :program_id 			
			AND `mahasiswa`.`semesterMhs` = :semester_matkul 			
			ORDER BY `NIM` ASC
			', ['id' => $matkul_tapel_id, 'prodi_id' => $data -> prodi_id, 'semester_matkul' => $data -> semester, 'program_id' => $data -> program_id]);
			*/
			
			$mahasiswa = \DB::select('
			SELECT `mahasiswa`.* 
			FROM `mahasiswa` 
			WHERE `mahasiswa`.`id` NOT IN (
			SELECT `mahasiswa_id`
			FROM `nilai`
			WHERE `nilai`.`matkul_tapel_id` = :id
			AND `nilai`.`jenis_nilai_id` = 0
			) 		
			AND statusMhs=1
			ORDER BY `NIM` ASC
			', ['id' => $matkul_tapel_id]);
			
			$anggota = \Siakad\Nilai::getNilaiAkhirMahasiswa($matkul_tapel_id) -> get();			
			return view('matkul.tapel.pesertakuliahproses', compact('data', 'angkatan', 'mahasiswa', 'anggota', 'matkul_tapel_id'));
		}
		
		private function filter($type)
		{
			$data = [];
			switch($type)
			{
				case 'prodi':
				$prodi = \Siakad\Prodi::all();
				$data['-'] = '-- PRODI --';
				foreach($prodi as $k) $data[$k -> id] = $k -> strata . ' ' . $k -> nama;
				break;	
				
				case 'semester':
				$ta = \Siakad\Tapel::orderBy('nama', 'desc') -> get();
				$data['-'] = '-- TAHUN AKADEMIK --';
				foreach($ta as $k) $data[$k -> id] = $k -> nama;
				break;		
			}
			
			return $data;
		}
		
		public function filtering()
		{
			$ta = intval(Input::get('ta')) > 0 ? Input::get('ta') : null;
			$prodi = intval(Input::get('prodi')) > 0 ? Input::get('prodi') : null;
			$perpage = intval(Input::get('perpage')) > 0 ? Input::get('perpage') : 200;
			$data = MatkulTapel::kelasKuliah($ta, $prodi) -> where('kurikulum_matkul.matkul_id', '<>', 0) -> paginate($perpage);				
			
			$prodi = $this -> filter('prodi');			
			$semester = $this -> filter('semester');
			
			return view('matkul.tapel.index', compact('data', 'semester', 'aktif', 'prodi'));
		}
		
		public function index()
		{
			$data = MatkulTapel::kelasKuliah() -> where('kurikulum_matkul.matkul_id', '<>', 0) -> paginate(25);		
			
			$prodi = $this -> filter('prodi');			
			$semester = $this -> filter('semester');
			
			return view('matkul.tapel.index', compact('data', 'semester', 'aktif', 'prodi'));
		}
		
		public function edit($matkul_tapel_id)
		{
			$matkul_tapel = MatkulTapel::find($matkul_tapel_id);
			
			$prodi = \Siakad\Prodi::where('id', $matkul_tapel -> prodi_id) -> first();
			$kelas = \Siakad\Kelas::where('id', $matkul_tapel -> kelas) -> first();
			$semester = \Siakad\Tapel::orderBy('nama') -> lists('nama', 'id');
			$matkuls = \Siakad\Matkul::getMatkulKurikulum($matkul_tapel -> prodi_id) -> get();
			foreach($matkuls as $m) $matkul[$m -> id] = $m -> kode . ' - ' . $m -> matkul . ' (' . $m -> sks_total . ' sks) ' . $m -> kurikulum .' - akt ' . $m -> angkatan;
			
			$aktif = $matkul_tapel -> tapel_id;
			
			$dosen = \Siakad\Dosen::orderBy('nama') -> lists('nama', 'id');
			foreach(range('A', 'Z') as $r) $kelas2[$r] = $r;
			return view('matkul.tapel.edit', compact('matkul_tapel', 'dosen', 'prodi', 'kelas', 'kelas2', 'semester', 'matkul', 'aktif'));
		}
		
		public function update($id)
		{
			$input = array_except(Input::all(), ['_token', '_method']);
			
			//check penugasan Dosen
			// if(!$this -> cekPenugasan($input)) return Redirect::back()  -> with('warning', 'Penugasan Dosen belum disetting.');
			
			if(MatkulTapel::where('id', '<>', $id)
			-> where('kurikulum_matkul_id', $input['kurikulum_matkul_id']) 
			-> where('prodi_id', $input['prodi_id']) 
			// -> where('dosen_id', $input['dosen_id']) 
			// -> where('sks', $input['sks']) 
			// -> where('semester', $input['semester']) 
			-> where('tapel_id', $input['tapel_id']) 
			-> where('kelas', $input['kelas']) 
			-> where('kelas2', $input['kelas2']) 
			-> exists()) return Redirect::back() -> with('warning', 'Data kelas kuliah sudah terdaftar, mohon periksa kembali');
			
			$mt = MatkulTapel::find($id) -> update($input);
			return Redirect::route('matkul.tapel.index') -> with('message', 'Data mata kuliah berhasil diubah');
		}
		
		public function getMatkulList()
		{
			$prodi = Input::get('prodi');
			$angkatan = Input::get('angkatan');
			$options = '';
			$matkuls = \Siakad\Matkul::getMatkulKurikulum($prodi, $angkatan) -> get();
			foreach($matkuls as $m) $options .= '<option value="'. $m -> id .'">' . $m -> kode . ' - ' . $m -> matkul . ' (' . $m -> sks_total . ' sks) ' . $m -> kurikulum .' - akt ' . $m -> angkatan .'</option>';
			return $options;
		}
		public function getAngkatanList()
		{
			$prodi = Input::get('prodi');
			$options = '<option value="0"> --ANGKATAN-- </option>';
			$kurikulums = \DB::select("
			SELECT DISTINCT k.`angkatan` FROM kurikulum k
			WHERE k.prodi_id = :prodi
			ORDER BY k.`angkatan`
			", ['prodi' => $prodi]);
			foreach($kurikulums as $k) $options .= '<option value="'. $k -> angkatan .'">' . $k -> angkatan .'</option>';
			return $options;
		}
		
		public function create()
		{
			$prodi = $this -> filter('prodi');	
			
			$semester = \Siakad\Tapel::orderBy('nama') -> get();
			foreach($semester as $s)
			{
				if($s -> aktif == 'y') $aktif = $s -> id;
				$tmp[$s -> id] = $s -> nama;
			}
			$semester = $tmp;
			
			$matkul = [];
			$dosen = \Siakad\Dosen::orderBy('nama') -> lists('nama', 'id');
			
			$kelas = \Siakad\Kelas::lists('nama', 'id');
			foreach(range('A', 'Z') as $r) $kelas2[$r] = $r;
			return view('matkul.tapel.create', compact('semester', 'matkul', 'dosen', 'prodi', 'aktif', 'kelas', 'kelas2'));
		}
		
		//check penugasan Dosen
		/*
			private function cekPenugasan($input)
			{
			return \Siakad\DosenPenugasan::where('dosen_id', $input['dosen_id']) 
			-> where('tapel_id', $input['tapel_id']) 
			-> where('prodi_id', $input['prodi_id'])
			-> exists();
			}
		*/
		
		public function store()
		{
			$input = array_except(Input::all(), '_token');
			
			//check penugasan Dosen
			//if(!$this -> cekPenugasan($input)) return Redirect::back()  -> with('warning', 'Penugasan Dosen belum disetting.');
			
			if(MatkulTapel::where('kurikulum_matkul_id', $input['kurikulum_matkul_id']) 
			-> where('prodi_id', $input['prodi_id']) 
			// -> where('dosen_id', $input['dosen_id']) 
			// -> where('sks', $input['sks']) 
			// -> where('semester', $input['semester']) 
			-> where('tapel_id', $input['tapel_id']) 
			-> where('kelas', $input['kelas']) 
			-> where('kelas2', $input['kelas2']) 
			-> exists()) return Redirect::back()  -> with('warning', 'Data kelas kuliah sudah terdaftar, mohon periksa kembali');
			
			unset($input['angkatan']);
			MatkulTapel::create($input);
			return Redirect::route('matkul.tapel.index') -> with('message', 'Data kelas kuliah berhasil dimasukkan');
		}
		
	public function destroy($id)
	{
	MatkulTapel::find($id) -> delete();
	return Redirect::route('matkul.tapel.index') -> with('message', 'Data kelas kuliah berhasil dihapus');
	}
	
	public function cetakFormAbsensi($id)
	{
	$data = MatkulTapel::getDataMataKuliah($id) -> first();
	$anggota = \Siakad\Nilai::getNilaiAkhirMahasiswa($id) -> get();
	return view('matkul.tapel.formabsensi', compact('data', 'anggota', 'id'));
	}
	}
		
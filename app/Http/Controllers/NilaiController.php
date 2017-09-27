<?php namespace Siakad\Http\Controllers;
	
	use Input;
	use Session;
	use Redirect;
	use Response;
	use Excel;
	
	use Siakad\Nilai;
	use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;	
	
	use Illuminate\Http\Request;
	
	class NilaiController extends Controller {
		
		private function checkBobotNilai($matkul_tapel_id)
		{
			$ag = \DB::select('
			SELECT 
			SUM(`bobot`) AS aggregate 
			FROM `jenis_nilai` 
			WHERE `id` IN(
			SELECT DISTINCT `nilai`.`jenis_nilai_id` 
			FROM `nilai` 
			WHERE `nilai`.`matkul_tapel_id` = :id AND `nilai`.`jenis_nilai_id` <> 0
			);', 
			['id' => $matkul_tapel_id]);
			if(!isset($ag[0] -> aggregate) or $ag[0] -> aggregate == null) return 0; else return intval($ag[0] -> aggregate);
		}
		
		public function import()
		{
			$tmp = null;
			$data = explode('|', Input::get('data'));			
			$matkul_tapel_id = Input::get('matkul_tapel_id');
			
			Nilai::where('matkul_tapel_id', $matkul_tapel_id) -> delete();
			
			$jenis_nilai = explode(';', $data[0]);
			
			if($jenis_nilai[0] === 'NILAI_V3')   										// V3
			{
				$count = count($jenis_nilai) - 1;
				foreach($data as $d) 
				{
					$t = explode(';', $d);
					if($t[0] === 'NILAI_V3') continue;
					for($i = 1; $i <= $count; $i++)
					{
						$tmp[] = [
						'mahasiswa_id' => $t[0],
						'jenis_nilai_id' => $jenis_nilai[$i],
						'nilai' => $t[$i],
						'matkul_tapel_id' => $matkul_tapel_id
						];
					}
				}
			}
			else																					// V2
			{
				foreach($data as $d) 
				{
					$t = explode(';', $d);
					$tmp[] = [
					'mahasiswa_id' => $t[0],
					'jenis_nilai_id' => $t[1],
					'nilai' => $t[2],
					'matkul_tapel_id' => $matkul_tapel_id
					];
				}
			}
			
			if($tmp === null) return Redirect::route('matkul.tapel.nilai', $matkul_tapel_id) -> withErrors(['IMPORT_FAILED' => 'Impor nilai GAGAL.']);	
			
			if(Nilai::insert($tmp))
			{
				$this -> hitungNilaiAkhir($matkul_tapel_id);
				return Redirect::route('matkul.tapel.nilai', $matkul_tapel_id) -> with('message', 'Impor nilai berhasil.');	
			}
			
			return Redirect::route('matkul.tapel.nilai', $matkul_tapel_id) -> withErrors(['IMPORT_FAILED' => 'Impor nilai GAGAL.']);	
		}
		
		public function hitungNilaiAkhir($matkul_tapel_id)
		{
			// Add checking routine: 22 Feb 2017
			$check = Nilai::where('jenis_nilai_id', '<>',  '0') -> where('matkul_tapel_id', $matkul_tapel_id) -> exists();
			if(!$check) return Redirect::route('matkul.tapel.nilai', $matkul_tapel_id) -> with('warning', 'Nilai belum dimasukkan.');	
			
			$check = $this -> checkBobotNilai($matkul_tapel_id);
			if($check < 100) return Redirect::route('matkul.tapel.nilai', $matkul_tapel_id) -> with('warning_raw', 'Nilai belum lengkap. Pilih / input Komponen Penilaian');	
			
			Nilai::where('jenis_nilai_id', '0')
			-> where('matkul_tapel_id', $matkul_tapel_id)
			-> delete();
			
			$query = "
			SELECT n.matkul_tapel_id, n.mahasiswa_id, numberToLetter(SUM(n.nilai * j.bobot /100)) AS `__FINAL__`, 0, now(), now()
			FROM nilai n 
			LEFT JOIN jenis_nilai j ON j.id = n.jenis_nilai_id
			WHERE matkul_tapel_id = :matkul_tapel_id 
			GROUP BY mahasiswa_id 
			ORDER BY mahasiswa_id
			";
			
			\DB::insert('
			INSERT INTO `nilai` (matkul_tapel_id, mahasiswa_id, nilai, jenis_nilai_id, updated_at, created_at) ' . $query . '
			', 
			['matkul_tapel_id' => $matkul_tapel_id]
			);
			
			//add flag: 22 Feb 2017
			\Siakad\MatkulTapel::where('id', $matkul_tapel_id) -> update(['sync' => 'y']);
			
			return Redirect::route('matkul.tapel.nilai', $matkul_tapel_id) -> with('message', 'Penghitungan nilai akhir berhasil.');	
		}
		
		/**
			* Display a listing of the resource.
			*
			* @return Response
		*/
		public function index($matkul_tapel_id)
		{
			$data = \Siakad\MatkulTapel::getDataMataKuliah($matkul_tapel_id) -> first();
			
			if(!In_array(\Auth::user() -> role_id, [1,2,8]) and $data -> dosen_id != \Auth::user() -> authable -> id) abort(401);
			$mhs = \DB::select('
			SELECT 
			`jenis_nilai`.`nama` AS `nama_nilai`, 
			`jenis_nilai`.`bobot` AS `bobot_nilai`, 
			`matkul_tapel_id`, `nilai`, `jenis_nilai_id`, `mahasiswa`.`id`, `mahasiswa`.`nama`, `mahasiswa`.`NIM` 
			FROM `matkul_tapel` `mt`
			INNER JOIN `nilai` ON `nilai`.`matkul_tapel_id` = `mt`.`id` 
			INNER JOIN `mahasiswa` ON `nilai`.`mahasiswa_id` = `mahasiswa`.`id`
			INNER JOIN `jenis_nilai` ON `nilai`.`jenis_nilai_id` = `jenis_nilai`.`id`
			WHERE `mt`.`id` = :id
			ORDER BY `mahasiswa`.`NIM` ASC, `jenis_nilai_id` DESC
			', 
			['id' => $matkul_tapel_id]
			);
			
			//ekspor nilai (04032017)
			$ejn = [];
			$edata = '';
			
			if(count($mhs) > 0)
			{
				foreach($mhs as $s)
				{
					//ekspor nilai 
					if(!in_array($s -> jenis_nilai_id, $ejn)) $ejn[] = $s -> jenis_nilai_id;
					$emhs[$s -> id][$s -> jenis_nilai_id] = $s -> nilai;
					
					$jenis_nilai[$s -> id][$s -> jenis_nilai_id] = [
					'nama_nilai' => $s -> nama_nilai . ' (' . $s -> bobot_nilai . '%)',
					'mt_id' => $s -> matkul_tapel_id,
					'nilai' => $s -> nilai,
					'jenis_nilai' => $s -> jenis_nilai_id,
					'id_mhs' => $s -> id,
					'nim' => $s -> NIM,
					'nama' => $s -> nama
					];
				}
				
				//ekspor nilai
				$edata[] = 'NILAI_V3;' . implode(';', $ejn);
				foreach($emhs as $k => $v) $edata[] = $k . ';' . implode(';', $v);
				$edata = implode('|', $edata);
			}
			else
			{
				$jenis_nilai = [];	
			}
			
			return view('nilai.index', compact('data', 'matkul_tapel_id', 'jenis_nilai', 'edata'));
		}
		
		public function export($matkul_tapel_id)
		{
			$this -> data = \Siakad\MatkulTapel::getDataMataKuliah($matkul_tapel_id) -> first();
			$title = 'Nilai - ' . $this -> data -> matkul . ' - ' . $this -> data -> singkatan . ' - ' . $this -> data -> ta;
			
			$this -> nilai = \DB::select('
			SELECT 
			`nilai`, `mahasiswa`.`nama`, `mahasiswa`.`NIM` 
			FROM `matkul_tapel` `mt`
			INNER JOIN `nilai` ON `nilai`.`matkul_tapel_id` = `mt`.`id` 
			INNER JOIN `mahasiswa` ON `nilai`.`mahasiswa_id` = `mahasiswa`.`id`
			WHERE `mt`.`id` = :id AND `nilai`.`jenis_nilai_id` = 0
			ORDER BY `mahasiswa`.`NIM`
			', 
			['id' => $matkul_tapel_id]
			);
			if(count($this -> nilai) < 1) return back() -> withErrors(['EMPTY' => 'Nilai tidak ditemukan']);
			
			Excel::create(str_slug($title), function($excel) use ($title){
				$excel
				->setTitle($title)
				->setCreator('schz')
				->setLastModifiedBy('Schz')
				->setManager('Schz')
				->setCompany('Schz. Co')
				->setKeywords('Al-Hikam, STAIMA, SIAKAD, STAIMA Al-Hikam Malang, '. $title)
				->setDescription($title);
				$excel->sheet('title', function($sheet){
					$nilai = $this -> nilai;
					$data = $this -> data;
					$sheet
					/* -> setOrientation('landscape') */
					-> setFontSize(12)
					-> loadView('nilai.export') -> with(compact('nilai', 'data'));
				});
				
			})->download('xlsx');
		}
		
		public function formNilai($matkul_tapel_id)
		{
			$data = \Siakad\MatkulTapel::getDataMataKuliah($matkul_tapel_id) -> first();
			
			if(!In_array(\Auth::user() -> role_id, [1, 2, 8]) and $data -> dosen_id != \Auth::user() -> authable -> id) abort(401);
			$mhs = \DB::select('
			SELECT 
			`jenis_nilai`.`nama` AS `nama_nilai`, 
			`jenis_nilai`.`bobot` AS `bobot_nilai`, 
			`matkul_tapel_id`, `nilai`, `jenis_nilai_id`, `mahasiswa`.`id`, `mahasiswa`.`nama`, `mahasiswa`.`NIM` 
			FROM `matkul_tapel` `mt`
			INNER JOIN `nilai` ON `nilai`.`matkul_tapel_id` = `mt`.`id` 
			INNER JOIN `mahasiswa` ON `nilai`.`mahasiswa_id` = `mahasiswa`.`id`
			INNER JOIN `jenis_nilai` ON `nilai`.`jenis_nilai_id` = `jenis_nilai`.`id`
			WHERE `mt`.`id` = :id
			ORDER BY `mahasiswa`.`NIM` ASC
			', 
			['id' => $matkul_tapel_id]
		);
		
		if(count($mhs) > 0)
		{
		foreach($mhs as $s)
		{
		$jenis_nilai[$s -> id][$s -> jenis_nilai_id] = [
		'nama_nilai' => $s -> nama_nilai,
		'mt_id' => $s -> matkul_tapel_id,
		'nilai' => $s -> nilai,
		'jenis_nilai' => $s -> jenis_nilai_id,
		'id_mhs' => $s -> id,
		'nim' => $s -> NIM,
		'nama' => $s -> nama
		];
		}
		}
		else
		{
		$jenis_nilai = [];	
		return back() -> withErrors(['no_data' => 'Belum ada Mahasiswa yang terdaftar']);
		}
		return view('nilai.formnilai', compact('data', 'matkul_tapel_id', 'jenis_nilai'));
		}
		
		public function store(Request $request, Nilai $nilai)
		{			
		$this -> validate($request, ['nilai' => ['required', 'numeric', 'between:0,100']]);
		
		$input = Input::except('_token');
		
		if(
		Nilai::where('jenis_nilai_id', $input['jenis_nilai_id'])
		-> where('matkul_tapel_id', $input['matkul_tapel_id'])
		-> where('mahasiswa_id', $input['mahasiswa_id'])
		-> exists()
		)
		{
		$nilai
		-> where('jenis_nilai_id', $input['jenis_nilai_id'])
		-> where('matkul_tapel_id', $input['matkul_tapel_id']) 
		-> where('mahasiswa_id', $input['mahasiswa_id']) 
		-> update(['nilai' => $input['nilai']]);
		}
		else
		{
		Nilai::create($input);
		}
		
		//add flag: 22 Feb 2017
		\Siakad\MatkulTapel::where('id', $input['matkul_tapel_id']) -> update(['sync' => 'n']);
		
		return Response::json(['success' => true, 'nilai' => $input['nilai']]);
		}
		
		public function destroy()
		{
		Nilai::where('jenis_nilai_id', Input::get('jenis_nilai_id'))
		-> where('matkul_tapel_id', Input::get('matkul_tapel_id'))
		-> delete();
		
		Nilai::where('jenis_nilai_id', '0')
		-> where('matkul_tapel_id', Input::get('matkul_tapel_id'))
		-> update(['nilai' => null]);
		
		return Response::json(['success' => true]);
		}
		}																																																																																																		
<?php
	
	namespace Siakad\Http\Controllers;
	
	use Input;
	use Session;
	use Response;
	use Redirect;
	
	use Siakad\JenisNilai;
	use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	use Illuminate\Http\Request;
	
	class JenisNilaiController extends Controller
	{
		protected $rules = [
		'nama' => ['required', 'min:3'],
		'bobot' => ['required', 'digits_between:1,100']
		];
		
		/**
			* Display a listing of the resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function index($matkul_tapel_id)
		{
			$types = JenisNilai::where('id', '!=', 0)->get();
			return view('jenisnilai.index', compact('types', 'matkul_tapel_id'));
		}
		
		/**
			* Show the form for creating a new resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function create($matkul_tapel_id)
		{
			return view('jenisnilai.create', compact('matkul_tapel_id'));
		}
		
		public function checkBobotNilai($matkul_tapel_id, $jenis_nilai_id)
		{
			$b = JenisNilai::whereId($jenis_nilai_id)->pluck('bobot');
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
			if(!isset($ag[0] -> aggregate) or $ag[0] -> aggregate == null) return 0; else return intval($ag[0] -> aggregate) + $b;
		}
		
		public function useExistingType($matkul_tapel_id, $jenis_nilai_id)
		{
			$bobot = $this -> checkBobotNilai($matkul_tapel_id, $jenis_nilai_id);
			if($bobot > 100) return Redirect::route('matkul.tapel.nilai', $matkul_tapel_id) -> withErrors('Total bobot nilai harus = 100%');
			
			$anggota = \DB::select('SELECT DISTINCT `mahasiswa_id` FROM `nilai` WHERE `matkul_tapel_id` = :id', ['id' => $matkul_tapel_id]);
			
			if( \Siakad\Nilai::where('jenis_nilai_id', $jenis_nilai_id)
			-> where('matkul_tapel_id', $matkul_tapel_id)
			-> where('mahasiswa_id', $anggota[0] -> mahasiswa_id)
			-> exists()
			)
			{
				return Redirect::route('matkul.tapel.nilai', $matkul_tapel_id) -> withErrors('Data nilai sudah terdaftar');
			}
			
			foreach($anggota as $a)
			{
				$data[] = ['jenis_nilai_id' => $jenis_nilai_id, 'matkul_tapel_id' => $matkul_tapel_id, 'mahasiswa_id' => $a -> mahasiswa_id, 'nilai' => null, 'created_at' => date('Y-m-d h:i:s'), 'updated_at' => date('Y-m-d h:i:s')];	
			}
			\Siakad\Nilai::insert($data);
			return Redirect::route('matkul.tapel.nilai', $matkul_tapel_id) -> with('message', 'Data berhasil dimasukkan');
		}
		
		/**
			* Store a newly created resource in storage.
			*
			* @param  \Illuminate\Http\Request  $request
			* @return \Illuminate\Http\Response
		*/
		public function store(Request $request, $matkul_tapel_id)
		{
			$this -> validate($request, $this->rules);
			
			$input = Input::all();
			$type = JenisNilai::create($input);
			
			$bobot = $this -> checkBobotNilai($matkul_tapel_id, $type -> id);
			if($bobot > 100) return Redirect::route('matkul.tapel.nilai', $matkul_tapel_id) -> withErrors('Total bobot nilai harus = 100%');
			
			$anggota = \DB::select('SELECT DISTINCT `mahasiswa_id` FROM `nilai` WHERE `matkul_tapel_id` = :id', ['id' => $matkul_tapel_id]);
			foreach($anggota as $a)
			{
				$data[] = ['jenis_nilai_id' => $type -> id, 'matkul_tapel_id' => $matkul_tapel_id, 'mahasiswa_id' => $a -> mahasiswa_id, 'nilai' => null, 'created_at' => date('Y-m-d h:i:s'), 'updated_at' => date('Y-m-d h:i:s')];	
			}
			\Siakad\Nilai::insert($data);
			
			return Redirect::route('matkul.tapel.nilai', $matkul_tapel_id) -> with('message', 'Data berhasil dimasukkan');
		}
		
		/**
			* Show the form for editing the specified resource.
			*
			* @param $id
			* @return \Illuminate\Http\Response
		*/
		public function edit($matkul_tapel_id, $id)
		{
			$jenis = JenisNilai::find($id);
			return view('jenisnilai.edit', compact('matkul_tapel_id', 'jenis'));
		}
		
		/**
			* Update the specified resource in storage.
			*
			* @param  \Illuminate\Http\Request  $request
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function update(Request $request, $matkul_tapel_id, $id)
		{
			$jenis = JenisNilai::find($id);
			
			$input = Input::except('_method');
			$jenis-> update($input);
			
			return Redirect::route('jenisnilai.index', [$matkul_tapel_id, $id]) -> with('message', 'Data berhasil diperbarui.');
		}
	}

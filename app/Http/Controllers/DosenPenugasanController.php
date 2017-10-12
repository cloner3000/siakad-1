<?php
	
	namespace Siakad\Http\Controllers;
	
	use Redirect;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Input;
	
	use Siakad\Dosen;
	use Siakad\DosenPenugasan;
	use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	class DosenPenugasanController extends Controller
	{		
		public function export()
		{
			$headers = array(
			"Content-type" => "text/csv",
			"Content-Disposition" => "attachment; filename=tugas_dosen.csv",
			"Pragma" => "no-cache",
			"Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
			"Expires" => "0"
			);
			
			$tugas = \Siakad\Jadwal::exportTugasDosen() -> get();
			$columns = ["NIP", "NIDN", "NIK/No.KTP", "Nama Lengkap Dosen", "Nama Prodi", "Prodi Homebase", "Jenjang Prodi", 
			"Rombel", "Nama Mata Kuliah", "Jumlah SKS", "Durasi Tatap Muka per SKS (menit)", "Hari", "Jam Mulai"];
			
			// hitung rombel
			$tmp = [];
			foreach($tugas as $t) 
			{
				if(array_key_exists($t -> dosen_id . $t -> matkul_id, $tmp)) $tmp[$t -> dosen_id . $t -> matkul_id] = $tmp[$t -> dosen_id . $t -> matkul_id] + 1;
				else $tmp[$t -> dosen_id . $t -> matkul_id] = 1;
			}
			
			$callback = function() use ($tugas, $columns, $tmp)
			{
				$file = fopen('php://output', 'w');
				fputcsv($file, [], ';');		
				fputcsv($file, $columns, ';');	
				fputcsv($file, [], ';');		
				fputcsv($file, [], ';');		
				fputcsv($file, [], ';');		
				$rombel = 1;
				foreach($tugas as $j) {
					$rombel = $tmp[$j -> dosen_id . $j -> matkul_id];
					$homebase = $j -> prodi_id_tugas == $j -> prodi_id_matkul ? 1 : 0;
					fputcsv($file, 
					[$j -> NIP,  $j -> NIDN,  $j -> NIK,  str_replace("'", '`', $j -> dosen) , str_replace("'", '`', $j -> prodi), $homebase,  $j -> jenjang,  
					$rombel,  str_replace("'", '`', $j -> matkul) , $j -> sks,  50,  $j -> hari, $j -> jam_mulai]
					, ';');
				}
				fclose($file);
			};
			return \Response::stream($callback, 200, $headers);
		}
		
		public function riwayat($id)
		{
			$dosen = Dosen::whereId($id) -> first();
			if(!$dosen) abort(404);
			
			$penugasan = DosenPenugasan::riwayatPenugasan($id) -> get();
			
			return view('dosen.penugasan.riwayat', compact('dosen', 'penugasan'));
		}
		
		public function filter()
		{
			$input = Input::all();
			
			$idosen = isset($input['dosen']) && $input['dosen'] !== '-' ?  $input['dosen'] : null;
			$iprodi = isset($input['prodi']) && $input['prodi'] !== '-' ?  $input['prodi'] : null;
			$ita = isset($input['ta']) && $input['ta'] !== '-' ?  $input['ta'] : null;
			
			$penugasan = DosenPenugasan::riwayatPenugasan($idosen, null, $iprodi, $ita) -> paginate(30);
			
			$prodi_tmp = \Siakad\Prodi::all();
			$prodi['-'] = '-- Semua PRODI --';
			foreach($prodi_tmp as $k) $prodi[$k -> id] = $k -> singkatan;
			
			$dosen_tmp = \Siakad\Dosen::orderBy('nama') -> get();
			$dosen['-'] = '-- Semua Dosen --';
			foreach($dosen_tmp as $k) $dosen[$k -> id] = $k -> nama;
			
			$ta_tmp = \Siakad\Tapel::orderBy('nama2', 'desc') -> get();
			$ta['-'] = '-- Semua Tahun Ajaran --';
			foreach($ta_tmp as $k) $ta[$k -> id] = $k -> nama;
			return view('dosen.penugasan.index', compact('penugasan', 'prodi', 'dosen', 'ta'));
		}
		
		public function index()
		{
			$prodi_tmp = \Siakad\Prodi::all();
			$prodi['-'] = '-- Semua PRODI --';
			foreach($prodi_tmp as $k) $prodi[$k -> id] = $k -> singkatan;
			
			$dosen_tmp = \Siakad\Dosen::orderBy('nama') -> get();
			$dosen['-'] = '-- Semua Dosen --';
			foreach($dosen_tmp as $k) $dosen[$k -> id] = $k -> nama;
			
			$ta_tmp = \Siakad\Tapel::orderBy('nama2', 'desc') -> get();
			$ta['-'] = '-- Semua Tahun Ajaran --';
			foreach($ta_tmp as $k) $ta[$k -> id] = $k -> nama;
			
			$penugasan = DosenPenugasan::riwayatPenugasan() -> paginate(30);
			return view('dosen.penugasan.index', compact('penugasan', 'prodi', 'dosen', 'ta'));
		}
		
		/**
			* Show the form for creating a new resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function create()
		{
			$dosen_list = Dosen::orderBy('nama') -> lists('nama', 'id');
			$prodi = \Siakad\Prodi::lists('nama', 'id');
			$tapel = \Siakad\Tapel::orderBy('nama2', 'desc') -> lists('nama', 'id');
			//$tmp = \DB::select('select id, left(nama, 9) as ta from tapel group by left(nama2, 4) order by left(nama2, 4) desc');
			//	foreach($tmp as $t) $tapel[$t -> id] = $t -> ta;
			return view('dosen.penugasan.create', compact('dosen_list', 'prodi', 'tapel'));
		}
		
		/**
			* Store a newly created resource in storage.
			*
			// * @param  \Illuminate\Http\Request  $request
			* @return \Illuminate\Http\Response
		*/
		public function store()
		{
		$input = Input::all();			
		DosenPenugasan::create($input);
		
		return Redirect::route('dosen.penugasan', $input['dosen_id']) -> with('success', 'Data Penugasan berhasil dimasukkan.');
		}
		
		/**
		* Show the form for editing the specified resource.
		*
		* @param  int  $id
		* @return \Illuminate\Http\Response
		*/
		public function edit($penugasan_id)
		{
		$dosen_list = Dosen::orderBy('nama') -> lists('nama', 'id');
		$penugasan = DosenPenugasan::find($penugasan_id);
		$prodi = \Siakad\Prodi::lists('nama', 'id');
		$tmp = \DB::select('select id, left(nama, 9) as ta from tapel group by left(nama2, 4) order by left(nama2, 4) desc');
		foreach($tmp as $t) $tapel[$t -> id] = $t -> ta;
		
		return view('dosen.penugasan.edit', compact('penugasan', 'prodi', 'dosen_list', 'tapel'));
		}
		
		/**
		* Update the specified resource in storage.
		*
		// * @param  \Illuminate\Http\Request  $request
		* @param  int  $id
		* @return \Illuminate\Http\Response
		*/
		public function update($penugasan_id)
		{
		$input = Input::except('_method');
		DosenPenugasan::find($penugasan_id) -> update($input);					
		return Redirect::route('dosen.penugasan', $input['dosen_id']) -> with('message', 'Data Penugasan Dosen berhasil diperbarui.');
		}
		
		public function show($penugasan_id)
		{
		$penugasan = DosenPenugasan::riwayatPenugasan(null, $penugasan_id) -> first();
		return view('dosen.penugasan.show', compact('penugasan'));
		}
		
		/**
		* Remove the specified resource from storage.
		*
		* @param  int  $id
		* @return \Illuminate\Http\Response
		*/
		public function destroy($id)
		{
		DosenPenugasan::find($id) -> delete();					
		return Redirect::route('dosen.penugasan.index') -> with('message', 'Data Penugasan Dosen berhasil dihapus.');
		}
		}
				
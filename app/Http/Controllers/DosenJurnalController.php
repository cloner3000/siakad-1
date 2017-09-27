<?php
	
	namespace Siakad\Http\Controllers;
	
	use Redirect;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Input;
	
	use Siakad\Dosen;
	use Siakad\DosenJurnal;
	use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	class DosenJurnalController extends Controller
	{		
		protected $rules = [
		'issn' => ['digits:9']
		];
		
		public function index()
		{
			$jurnal = DosenJurnal::jurnal() -> paginate(30);
			return view('dosen.jurnal.index', compact('jurnal'));
		}
		
		/**
			* Show the form for creating a new resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function create()
		{
			$dosen_list = Dosen::orderBy('nama') -> lists('nama', 'id');
			return view('dosen.jurnal.create', compact('dosen_list'));
		}
		
		/**
			* Store a newly created resource in storage.
			*
			// * @param  \Illuminate\Http\Request  $request
			* @return \Illuminate\Http\Response
		*/
		public function store(Request $request)
		{
			$this -> validate($request, $this -> rules);
			$input = Input::all();
			
			DosenJurnal::create($input);
			
			return Redirect::route('dosen.jurnal.index') -> with('success', 'Data Jurnal berhasil dimasukkan.');
		}
		
		/**
			* Show the form for editing the specified resource.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function edit($jurnal_id)
		{
			$dosen_list = Dosen::orderBy('nama') -> lists('nama', 'id');
			$jurnal = DosenJurnal::find($jurnal_id);
			
			return view('dosen.jurnal.edit', compact('jurnal', 'dosen_list'));
		}
		
		/**
			* Update the specified resource in storage.
			*
			// * @param  \Illuminate\Http\Request  $request
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function update(Request $request, $jurnal_id)
		{
			$this -> validate($request, $this -> rules);
			$input = Input::except('_method');
			DosenJurnal::find($jurnal_id) -> update($input);					
			return Redirect::route('dosen.jurnal.index') -> with('message', 'Data Jurnal Dosen berhasil diperbarui.');
		}
		
		/* 		public function show($dosen_id, $jurnal_id)
			{
			$jurnal = DosenJurnal::riwayatJurnal(null, $jurnal_id) -> first();
			return view('dosen.jurnal.show', compact('jurnal'));
		} */
		
		/**
			* Remove the specified resource from storage.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function destroy($dosen_id, $jurnal_id)
		{
			DosenJurnal::find($jurnal_id) -> delete();		
			return Redirect::route('dosen.jurnal', $dosen_id) -> with('message', 'Data Jurnal Dosen berhasil dihapus.');
		}
		
		public function export()
		{
			$headers = array(
			"Content-type" => "text/csv",
			"Content-Disposition" => "attachment; filename=jurnal_dosen.csv",
			"Pragma" => "no-cache",
			"Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
			"Expires" => "0"
			);
			
			$jurnals = DosenJurnal::jurnal() -> get();
			$columns = ["NIP", "NIDN", "NIK/No.KTP", "Nama Lengkap Dosen", "Judul Artikel", "Nama Jurnal", "Alamat Website Jurnal", "Level Jurnal", "Penerbit", "No. ISSN", "Akreditasi", "Tahun Terbit"];
			
			$callback = function() use ($jurnals, $columns)
			{
				$file = fopen('php://output', 'w');
				fputcsv($file, [], ';');				
				fputcsv($file, $columns, ';');	
				fputcsv($file, [], ';');		
				fputcsv($file, [], ';');					
				foreach($jurnals as $j) {
					fputcsv($file, 
					[$j -> NIP,  $j -> NIDN,  $j -> NIK,  str_replace("'", '`', $j -> nama) , str_replace("'", '`', $j -> judul_artikel), str_replace("'", '`', $j -> nama_jurnal) ,  $j -> website_jurnal,  $j -> level_jurnal,  str_replace("'", '`', $j -> penerbit) , $j -> issn,  $j -> akreditasi,  $j -> tahun_terbit]
					, ';');
				}
				fclose($file);
			};
			return \Response::stream($callback, 200, $headers);
		}
	}

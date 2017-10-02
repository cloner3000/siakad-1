<?php
	
	namespace Siakad\Http\Controllers;
	
	use Redirect;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Input;
	
	use Siakad\Dosen;
	use Siakad\DosenBuku;
	use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	class DosenBukuController extends Controller
	{		
		protected $rules = [
		//'issn' => ['digits:9']
		];
		
		public function index()
		{
			$buku = DosenBuku::buku() -> paginate(30);
			return view('dosen.buku.index', compact('buku'));
		}
		
		/**
			* Show the form for creating a new resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function create()
		{
			$dosen_list = Dosen::orderBy('nama') -> lists('nama', 'id');
			return view('dosen.buku.create', compact('dosen_list'));
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
			
			DosenBuku::create($input);
			
			return Redirect::route('dosen.buku.index') -> with('success', 'Data Buku berhasil dimasukkan.');
		}
		
		/**
			* Show the form for editing the specified resource.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function edit($buku_id)
		{
			$dosen_list = Dosen::orderBy('nama') -> lists('nama', 'id');
			$buku = DosenBuku::find($buku_id);
			
			return view('dosen.buku.edit', compact('buku', 'dosen_list'));
		}
		
		/**
			* Update the specified resource in storage.
			*
			// * @param  \Illuminate\Http\Request  $request
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function update(Request $request, $buku_id)
		{
			$this -> validate($request, $this -> rules);
			$input = Input::except('_method');
			DosenBuku::find($buku_id) -> update($input);					
			return Redirect::route('dosen.buku.index') -> with('message', 'Data Buku Dosen berhasil diperbarui.');
		}
		
		/**
			* Remove the specified resource from storage.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function destroy($dosen_id, $buku_id)
		{
			DosenBuku::find($buku_id) -> delete();		
			return Redirect::route('dosen.buku', $dosen_id) -> with('message', 'Data Buku Dosen berhasil dihapus.');
		}
		
		public function export()
		{
			$headers = array(
			"Content-type" => "text/csv",
			"Content-Disposition" => "attachment; filename=buku_dosen.csv",
			"Pragma" => "no-cache",
			"Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
			"Expires" => "0"
			);
			
			$bukus = DosenBuku::buku() -> get();
			$columns = ["NIP", "NIDN", "NIK/No.KTP", "Nama Lengkap Dosen", "Judul Buku", "Klasifikasi", "Penerbit", "No. ISBN", "Tahun Terbit"];
			
			$callback = function() use ($bukus, $columns)
			{
				$file = fopen('php://output', 'w');
				fputcsv($file, [], ';');				
				fputcsv($file, $columns, ';');	
				fputcsv($file, [], ';');		
				fputcsv($file, [], ';');					
				foreach($bukus as $j) {
					fputcsv($file, 
					[$j -> NIP,  $j -> NIDN,  $j -> NIK,  str_replace("'", '`', $j -> nama) , str_replace("'", '`', $j -> judul), $j -> klasifikasi,  str_replace("'", '`', $j -> penerbit) , $j -> isbn, $j -> tahun_terbit]
					, ';');
				}
				fclose($file);
			};
			return \Response::stream($callback, 200, $headers);
		}
	}

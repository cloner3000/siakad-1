<?php
	
	namespace Siakad\Http\Controllers;
	
	use Redirect;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Input;
	
	use Siakad\Dosen;
	use Siakad\DosenKepangkatan;
	use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	class DosenKepangkatanController extends Controller
	{		
		public function riwayat($id)
		{
			$dosen = Dosen::whereId($id) -> first();
			if(!$dosen) abort(404);
			$kepangkatan = DosenKepangkatan::riwayatKepangkatan($id) -> get();
			
			return view('dosen.kepangkatan.riwayat', compact('dosen', 'kepangkatan'));
		}
		
		public function index()
		{
			$kepangkatan = DosenKepangkatan::riwayatKepangkatan() -> paginate(30);
			return view('dosen.kepangkatan.index', compact('kepangkatan'));
		}
		
		/**
			* Show the form for creating a new resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function create($id)
		{
			$dosen = Dosen::find($id);
			return view('dosen.kepangkatan.create', compact('dosen'));
		}
		
		/**
			* Store a newly created resource in storage.
			*
			// * @param  \Illuminate\Http\Request  $request
			* @return \Illuminate\Http\Response
		*/
		public function store($id)
		{
			$input = Input::all();
			$input['dosen_id'] = $id;
			
			DosenKepangkatan::create($input);
			
			return Redirect::route('dosen.kepangkatan', $id) -> with('success', 'Data Kepangkatan berhasil dimasukkan.');
		}
		
		/**
			* Show the form for editing the specified resource.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function edit($dosen_id, $kepangkatan_id)
		{
			$dosen = Dosen::find($dosen_id);
			$kepangkatan = DosenKepangkatan::find($kepangkatan_id);
			
			return view('dosen.kepangkatan.edit', compact('kepangkatan', 'dosen'));
		}
		
		/**
			* Update the specified resource in storage.
			*
			// * @param  \Illuminate\Http\Request  $request
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function update($dosen_id, $kepangkatan_id)
		{
			$input = Input::except('_method');
			DosenKepangkatan::find($kepangkatan_id) -> update($input);					
			return Redirect::route('dosen.kepangkatan', $dosen_id) -> with('message', 'Data Kepangkatan Dosen berhasil diperbarui.');
		}
		
		/* 		public function show($dosen_id, $kepangkatan_id)
			{
			$kepangkatan = DosenKepangkatan::riwayatKepangkatan(null, $kepangkatan_id) -> first();
			return view('dosen.kepangkatan.show', compact('kepangkatan'));
		} */
		
		/**
			* Remove the specified resource from storage.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function destroy($dosen_id, $kepangkatan_id)
		{
			DosenKepangkatan::find($kepangkatan_id) -> delete();		
			return Redirect::route('dosen.kepangkatan', $dosen_id) -> with('message', 'Data Kepangkatan Dosen berhasil dihapus.');
		}
	}

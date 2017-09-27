<?php
	
	namespace Siakad\Http\Controllers;
	
	use Redirect;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Input;
	
	use Siakad\Skripsi;
	use Siakad\BimbinganSkripsi;
	use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	class BimbinganSkripsiController extends Controller
	{
		public function show($id)
		{
			$skripsi = BimbinganSkripsi::whereId($id) -> first();
			if(!$skripsi) abort(404);
			return view('mahasiswa.skripsi.bimbingan.show', compact('skripsi'));
		}
		
		public function index()
		{
			$skripsi = BimbinganSkripsi::all();
			return view('mahasiswa.skripsi.bimbingan.index', compact('skripsi'));
		}
		
		/**
			* Show the form for creating a new resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function create($id)
		{
			$skripsi = Skripsi::find($id);
			return view('mahasiswa.skripsi.bimbingan.create', compact('skripsi'));
		}
		
		/**
			* Store a newly created resource in storage.
			*
			// * @param  \Illuminate\Http\Request  $request
			* @return \Illuminate\Http\Response
		*/
		public function store($id)
		{
			$input= Input::except('_token');
			$input['skripsi_id'] = $id;
			if(BimbinganSkripsi::create($input)) $message = 'Data Bimbingan Skripsi berhasil dimasukkan.';		
			else $message = 'Input data Bimbingan Skripsi gagal.';		
			return Redirect::route('skripsi.show', $id) -> with('message', $message);
		}
		
		/**
			* Show the form for editing the specified resource.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function edit($skripsi, $bimbingan)
		{
			$bimbingan = BimbinganSkripsi::find($bimbingan);
			$skripsi = $bimbingan -> skripsi;
			return view('mahasiswa.skripsi.bimbingan.edit', compact('skripsi', 'bimbingan'));
		}
		
		/**
			* Update the specified resource in storage.
			*
			// * @param  \Illuminate\Http\Request  $request
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function update($skripsi, $bimbingan)
		{
			$input = Input::except('_method');
			$bimbingan = BimbinganSkripsi::find($bimbingan);
			if($bimbingan -> update($input)) $message = 'Data Bimbingan Skripsi berhasil diubah.';		
			else $message = 'Ubah data Bimbingan Skripsi gagal.';	
			return Redirect::route('skripsi.show', $skripsi) -> with('message', $message);
		}
		
		/**
			* Remove the specified resource from storage.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function destroy($id)
		{
			//
		}
	}

<?php
	
	namespace Siakad\Http\Controllers;
	
	use Redirect;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Input;
	
	use Siakad\Dosen;
	use Siakad\DosenFungsional;
	use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	class DosenFungsionalController extends Controller
	{		
		public function riwayat($id)
		{
			$dosen = Dosen::whereId($id) -> first();
			if(!$dosen) abort(404);
			$fungsional = DosenFungsional::riwayatFungsional($id) -> get();
			
			return view('dosen.fungsional.riwayat', compact('dosen', 'fungsional'));
		}
		
		public function index()
		{
			$fungsional = DosenFungsional::riwayatFungsional() -> paginate(30);
			return view('dosen.fungsional.index', compact('fungsional'));
		}
		
		/**
			* Show the form for creating a new resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function create($id)
		{
			$dosen = Dosen::find($id);
			return view('dosen.fungsional.create', compact('dosen'));
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
			
			DosenFungsional::create($input);
			
			return Redirect::route('dosen.fungsional', $id) -> with('success', 'Data Fungsional berhasil dimasukkan.');
		}
		
		/**
			* Show the form for editing the specified resource.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function edit($dosen_id, $fungsional_id)
		{
			$dosen = Dosen::find($dosen_id);
			$fungsional = DosenFungsional::find($fungsional_id);
			
			return view('dosen.fungsional.edit', compact('fungsional', 'dosen'));
		}
		
		/**
			* Update the specified resource in storage.
			*
			// * @param  \Illuminate\Http\Request  $request
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function update($dosen_id, $fungsional_id)
		{
			$input = Input::except('_method');
			DosenFungsional::find($fungsional_id) -> update($input);					
			return Redirect::route('dosen.fungsional', $dosen_id) -> with('message', 'Data Fungsional Dosen berhasil diperbarui.');
		}
		
		/* 		public function show($dosen_id, $fungsional_id)
			{
			$fungsional = DosenFungsional::riwayatFungsional(null, $fungsional_id) -> first();
			return view('dosen.fungsional.show', compact('fungsional'));
		} */
		
		/**
			* Remove the specified resource from storage.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function destroy($dosen_id, $fungsional_id)
		{
			DosenFungsional::find($fungsional_id) -> delete();		
			return Redirect::route('dosen.fungsional', $dosen_id) -> with('message', 'Data Fungsional Dosen berhasil dihapus.');
		}
	}

<?php
	
	namespace Siakad\Http\Controllers;
	
	use Redirect;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Input;
	
	use Siakad\Dosen;
	use Siakad\DosenPenelitian;
	use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	class DosenPenelitianController extends Controller
	{		
		/*public function riwayat($id)
		{
			$dosen = Dosen::whereId($id) -> first();
			if(!$dosen) abort(404);
			$penelitian = DosenPenelitian::riwayatPenelitian($id) -> get();
			
			return view('dosen.penelitian.riwayat', compact('dosen', 'penelitian'));
		}*/
		
		public function index()
		{
			$penelitian = DosenPenelitian::riwayatPenelitian() -> paginate(30);
			return view('dosen.penelitian.index', compact('penelitian'));
		}
		
		/**
			* Show the form for creating a new resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function create()
		{
			$dosen_list = Dosen::orderBy('nama') -> lists('nama', 'id');
			return view('dosen.penelitian.create', compact('dosen_list'));
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
			
			DosenPenelitian::create($input);
			
			return Redirect::route('dosen.penelitian.index') -> with('success', 'Data Penelitian berhasil dimasukkan.');
		}
		
		/**
			* Show the form for editing the specified resource.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function edit($penelitian_id)
		{
			$dosen_list = Dosen::orderBy('nama') -> lists('nama', 'id');
			$penelitian = DosenPenelitian::find($penelitian_id);
			return view('dosen.penelitian.edit', compact('penelitian', 'dosen'));
		}
		
		/**
			* Update the specified resource in storage.
			*
			// * @param  \Illuminate\Http\Request  $request
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function update($penelitian_id)
		{
			$input = Input::except('_method');
			DosenPenelitian::find($penelitian_id) -> update($input);					
			return Redirect::route('dosen.penelitian.index') -> with('message', 'Data Penelitian Dosen berhasil diperbarui.');
		}
		
		/* 		public function show($dosen_id, $penelitian_id)
			{
			$penelitian = DosenPenelitian::riwayatPenelitian(null, $penelitian_id) -> first();
			return view('dosen.penelitian.show', compact('penelitian'));
		} */
		
		/**
			* Remove the specified resource from storage.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function destroy($penelitian_id)
		{
			DosenPenelitian::find($penelitian_id) -> delete();		
			return Redirect::route('dosen.penelitian.index') -> with('message', 'Data Penelitian Dosen berhasil dihapus.');
		}
		public function export()
		{

		}
	}

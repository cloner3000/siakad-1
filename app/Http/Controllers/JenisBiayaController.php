<?php
	
	namespace Siakad\Http\Controllers;
	
	use Illuminate\Support\Facades\Input;
	use Redirect;
	
	use Illuminate\Http\Request;
	
	use Siakad\JenisBiaya;
	use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	class JenisBiayaController extends Controller
	{
		protected $rules = ['nama' => ['required', 'min:2']];
		/**
			* Display a listing of the resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function index()
		{
			$jbiaya = JenisBiaya::all();
			return view('jenisbiaya.index', compact('jbiaya'));
		}
		
		/**
			* Show the form for creating a new resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function create()
		{
			return view('jenisbiaya.create');
		}
		
		/**
			* Store a newly created resource in storage.
			*
			* @param  \Illuminate\Http\Request  $request
			* @return \Illuminate\Http\Response
		*/
		public function store(Request $request)
		{
			$this -> validate($request, $this -> rules);
			$input = Input::except('_token');
			
			JenisBiaya::create($input);
			return Redirect::route('jenisbiaya.index') -> with('message', 'Jenis pembayaran berhasil disimpan');
		}
		
		/**
			* Display the specified resource.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function show($id)
		{
			//
		}
		
		/**
			* Show the form for editing the specified resource.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function edit($id)
		{
			$jbiaya = JenisBiaya::find($id);
			return view('jenisbiaya.edit', compact('jbiaya'));
		}
		
		/**
			* Update the specified resource in storage.
			*
			* @param  \Illuminate\Http\Request  $request
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function update(Request $request, $id)
		{
			$this -> validate($request, $this -> rules);
			$input = Input::except('_token', '_method');
			JenisBiaya::find($id) -> update($input);
			return Redirect::route('jenisbiaya.index') -> with('message', 'Jenis pembayaran berhasil diperbarui');
		}
		
		/**
			* Remove the specified resource from storage.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function destroy($id)
		{
			JenisBiaya::find($id) -> delete();
			return Redirect::route('jenisbiaya.index') -> with('message', 'Jenis pembayaran berhasil dihapus');			
		}
	}

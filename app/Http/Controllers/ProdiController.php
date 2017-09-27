<?php
	
	namespace Siakad\Http\Controllers;
	
	use Redirect;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Input;
	
	use Siakad\Prodi;
	use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	class ProdiController extends Controller
	{
		/**
			* Display a listing of the resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function index()
		{
			$prodi = Prodi::all();
			return view('prodi.index', compact('prodi'));
		}
		
		/**
			* Show the form for creating a new resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function create()
		{
			return view('prodi.create');
		}
		
		/**
			* Store a newly created resource in storage.
			*
			// * @param  \Illuminate\Http\Request  $request
			* @return \Illuminate\Http\Response
		*/
		public function store()
		{
			$input= Input::except('_token');
			$input['singkatan'] = strtoupper($input['singkatan']);
			Prodi::create($input);
			
			$last_role = \Siakad\Role::where('name', 'Prodi') -> orderBy('id', 'desc') -> first();
			\Siakad\Role::create(['id' => $last_role -> id + 1, 'name' => 'Prodi', 'sub' => $input['singkatan']]);
			
			return Redirect::route('prodi.index') -> with('message', 'Data PRODI berhasil dimasukkan.');
		}
		
		/**
			* Show the form for editing the specified resource.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function edit($id)
		{
			$prodi = Prodi::find($id);
			$role = \Siakad\Role::where('name', 'Prodi') -> where('sub', $prodi -> singkatan) -> first();
			return view('prodi.edit', compact('prodi', 'role'));
		}
		
		/**
			* Update the specified resource in storage.
			*
			// * @param  \Illuminate\Http\Request  $request
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function update($id)
		{
			$input = Input::except('_method');
			$input['singkatan'] = strtoupper($input['singkatan']);
			
			\Siakad\Role::find($input['role_id']) -> update(['sub' => $input['singkatan']]);
			unset($input['role_id']);
			
			Prodi::find($id) -> update($input);
			
			return Redirect::route('prodi.index') -> with('message', 'Data Prodi berhasil diperbarui.');
		}
		
		/**
			* Remove the specified resource from storage.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function destroy($id)
		{
			$prodi = Prodi::find($id);
			\Siakad\Role::where('name', 'Prodi') -> where('sub', $prodi -> singkatan) -> delete();
			$prodi -> delete();
			return Redirect::route('prodi.index') -> with('success', 'Data PRODI berhasil dihapus.');
		}
	}

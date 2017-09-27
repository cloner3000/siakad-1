<?php
	
	namespace Siakad\Http\Controllers;
	
	use Input;
	use Redirect;
	use Illuminate\Http\Request;
	
	use Siakad\Pmb;
	
	class PmbController extends Controller
	{
		protected $rules = [
		'nama' => ['required'],
		'mulai' => ['required', 'date', 'date_format:d-m-Y'],
		'selesai' => ['date', 'date_format:d-m-Y', 'after:mulai']
		];
		public function index()
		{
			$pmb = Pmb::orderBy('mulai', 'desc') -> get();
			// $prodi = \Siakad\Prodi::orderBy('nama') -> lists('singkatan', 'id');
			// return view('pmb.index', compact('pmb', 'prodi'));
			return view('pmb.index', compact('pmb'));
		}
		
		/**
			* Show the form for creating a new resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function create()
		{
			// $prodi = \Siakad\Prodi::orderBy('nama') -> lists('singkatan', 'id');
			// return view('pmb.create', compact('prodi'));
			return view('pmb.create');
		}
		
		/**
			* Store a newly created resource in storage.
			*
			// * @param  \Illuminate\Http\Request  $request
			* @return \Illuminate\Http\Response
		*/
		public function store(Request  $request)
		{
			$this -> validate($request, $this -> rules);
			$input= Input::except('_token');
			$input['mulai'] = date('Y-m-d', strtotime($input['mulai']));
			$input['selesai'] = date('Y-m-d', strtotime($input['selesai']));
			// $input['kuota'] = json_encode($input['kuota']);
			if(isset($input['buka']) && $input['buka'] == 'y') Pmb::where(['buka' => 'y']) -> update(['buka' => 'n']);		
			Pmb::create($input);			
			return Redirect::route('pmb.index') -> with('success', 'Data PMB berhasil dimasukkan.');
		}
		
		/**
			* Show the form for editing the specified resource.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function edit($id)
		{
			$pmb = Pmb::find($id);
			// $prodi = \Siakad\Prodi::orderBy('nama') -> lists('singkatan', 'id');
			// $kuota = json_decode($pmb -> kuota);
			// return view('pmb.edit', compact('pmb', 'prodi', 'kuota'));
			return view('pmb.edit', compact('pmb'));
		}
		
		/**
			* Update the specified resource in storage.
			*
			// * @param  \Illuminate\Http\Request  $request
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function update(Request $request, $id)
		{
			$this -> validate($request, $this -> rules);
			$input = Input::except('_method');
			$input['mulai'] = date('Y-m-d', strtotime($input['mulai']));
			$input['selesai'] = date('Y-m-d', strtotime($input['selesai']));
			// $input['kuota'] = json_encode($input['kuota']);
			if(isset($input['buka']) && $input['buka'] == 'y') Pmb::where(['buka' => 'y']) -> update(['buka' => 'n']);	
			Pmb::find($id) -> update($input);			
			return Redirect::route('pmb.index') -> with('success', 'Data PMB berhasil diperbarui.');
		}
		
		/**
			* Remove the specified resource from storage.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function destroy($id)
		{
			Pmb::find($id) -> delete();			
			return Redirect::route('pmb.index') -> with('success', 'Data PMB berhasil dihapus.');
		}
	}

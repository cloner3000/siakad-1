<?php
	
	namespace Siakad\Http\Controllers;
	
	use Illuminate\Support\Facades\Input;
	// use Session;
	use Redirect;
	
	use Siakad\Tapel;
	use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	use Illuminate\Http\Request;
	
	class TapelController extends Controller
	{		
		protected $rules = [
		'batasRegistrasi' => ['required', 'date', 'date_format:Y-m-d'],
		'mulai' => ['required', 'date', 'date_format:Y-m-d'],
		'selesai' => ['required', 'date', 'date_format:Y-m-d', 'after:mulai'],
		'mulaiKrs' => ['required', 'date', 'date_format:Y-m-d'],
		'selesaiKrs' => ['required','date', 'date_format:Y-m-d', 'after:mulaiKrs']
		];
		
		public function __construct()
		{
			$this->middleware('auth');	
		}
		/**
			* Display a listing of the resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function index()
		{
			$tapel = Tapel::orderBy('nama2')->get();
			return view('tapel.index', compact('tapel'));
		}
		
		/**
			* Show the form for creating a new resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function create()
		{
			return view('tapel.create');
		}
		
		/**
			* Store a newly created resource in storage.
			*
			* @param  \Illuminate\Http\Request  $request
			* @return \Illuminate\Http\Response
		*/
		public function store(Request $request)
		{			
			$input = Input::all();
			if(isset($input['aktif']) && $input['aktif'] == 'y') Tapel::where(['aktif' => 'y'])->update(['aktif' => 'n']);
			$input['nama'] = $input['tahun'] . '/' . intval($input['tahun'] + 1) . ' ' . $input['semester'];
			
			$input['nama2'] = $input['tahun'];
			$input['nama2'] .= $input['semester'] == 'Ganjil' ? 1 : 2;
			
			unset($input['tahun']);
			unset($input['semester']);
			Tapel::create($input);
			return Redirect::route('tapel.index') -> with('message', 'Data berhasil dimasukkan');
		}
		
		/**
			* Display the specified resource.
			*
			* @param  int  Tapel $tapel
			* @return \Illuminate\Http\Response
		*/
		public function show(Tapel $tapel)
		{
			return view('tapel.show', compact('tapel'));
		}
		
		/**
			* Show the form for editing the specified resource.
			*
			* @param Tapel $tapel
			* @return \Illuminate\Http\Response
		*/
		public function edit(Tapel $tapel)
		{
			$nama = explode(' ', $tapel -> nama);
			
			$tapel -> tahun = explode('/', $nama[0])[0];
			$tapel -> semester = $nama[1];
			return view('tapel.edit', compact('tapel'));
		}
		
		/**
			* Update the specified resource in storage.
			*
			* @param  \Illuminate\Http\Request  $request
			* @param  int  Tapel $tapel
			* @return \Illuminate\Http\Response
		*/
		public function update(Request $request, Tapel $tapel)
		{
			// $this -> validate($request, $this->rules);
			
			$input = array_except(Input::all(), '_method');
			if(isset($input['aktif']) && $input['aktif'] == 'y') Tapel::where('id', '<>', $tapel -> id) -> update(['aktif' => 'n']);
			
			$input['nama'] = $input['tahun'] . '/' . intval($input['tahun'] + 1) . ' ' . $input['semester'];
			
			$input['nama2'] = $input['tahun'];
			$input['nama2'] .= $input['semester'] == 'Ganjil' ? 1 : 2;
			
			unset($input['tahun']);
			unset($input['semester']);
			
			$tapel-> update($input);
			
			return Redirect::route('tapel.index', $tapel->id) -> with('message', 'Data berhasil diperbarui.');
		}
		
	/**
	* Remove the specified resource from storage.
	*
	* @param  int  Tapel $tapel
	* @return \Illuminate\Http\Response
	*/
	public function destroy(Tapel $tapel)
	{
	//
	}
	}
		
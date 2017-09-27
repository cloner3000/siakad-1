<?php
	
	namespace Siakad\Http\Controllers;
	
	use Redirect;
	
	use Siakad\Kalender;
	use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Input;
	
	class KalenderController extends Controller
	{
		protected $rules = [
		'mulai' => ['required', 'date', 'date_format:Y-m-d'],
		'sampai' => ['date', 'date_format:Y-m-d', 'after:mulai']
		];
		/**
			* Display a listing of the resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function index($t = null)
		{
			$years = \DB::select('SELECT DISTINCT LEFT(`mulai`, 4) AS `year` FROM `kalender`');
			foreach($years as $year) $tahun[$year -> year] = $year -> year;
			
			$query = Kalender::query();
			if($t != null) $query = $query -> where('mulai', 'LIKE', $t . '%');
			$query = $query -> orderBy('mulai');
			
			$agenda = $query -> get();
			
			$file = \Siakad\FileEntry::where('tipe', '6') -> orderBy('id', 'desc') -> first();
			return view('kalender.index', compact('tahun', 'agenda', 'file'));
		}
		
		public function publicIndex($t = null)
		{
			$years = \DB::select('SELECT DISTINCT LEFT(`mulai`, 4) AS `year` FROM `kalender`');
			foreach($years as $year) $tahun[$year -> year] = $year -> year;
			
			$query = Kalender::query();
			if($t != null) $query = $query -> where('mulai', 'LIKE', $t . '%');
			$query = $query -> orderBy('mulai');
			
			$agenda = $query -> get();
			
			$file = \Siakad\FileEntry::where('tipe', '6') -> orderBy('id', 'desc') -> first();
			return view('kalender.public', compact('tahun', 'agenda', 'file'));
		}
		
		/**
			* Show the form for creating a new resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function create()
		{
			return view('kalender.create');
		}
		
		/**
			* Store a newly created resource in storage.
			*
			* @param  \Illuminate\Http\Request  $request
			* @return \Illuminate\Http\Response
		*/
		public function store(Request  $request)
		{
			$mulai = $request -> mulai3 . '-' . str_pad($request -> mulai2, 2, '0', STR_PAD_LEFT) . '-' . str_pad($request -> mulai1, 2, '0', STR_PAD_LEFT); 
			$request -> merge(['mulai' => $mulai]);
			$sampai = $request -> sampai3 . '-' . str_pad($request -> sampai2, 2, '0', STR_PAD_LEFT) . '-' . str_pad($request -> sampai1, 2, '0', STR_PAD_LEFT); 
			if($sampai != $mulai AND $request -> sampai3 != '') $request -> merge(['sampai' => $sampai]);
			$this -> validate($request, $this->rules);
			
			$input = array_only(Input::all(), ['mulai', 'sampai', 'kegiatan', 'jenis_kegiatan']);
			Kalender::create($input);
			
			$agenda = Kalender::orderBy('mulai') -> get();
			return Redirect::route('kalender.index') -> with('message', 'Data kegiatan berhasil diperbarui.');
		}
		
		/**
			* Display the specified resource.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function show($id)
		{
		
		}
		
		/**
			* Show the form for editing the specified resource.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function edit($id)
		{
			$agenda = Kalender::where('id', $id) -> first();
			$mulai = explode('-', $agenda->mulai);
			$agenda -> mulai1 = intval($mulai[2]);
			$agenda -> mulai2 = intval($mulai[1]);
			$agenda -> mulai3 = $mulai[0];
			if($agenda->sampai != '')
			{
				$sampai = explode('-', $agenda->sampai);
				$agenda -> sampai1 = intval($sampai[2]);
				$agenda -> sampai2 = intval($sampai[1]);
				$agenda -> sampai3 = $sampai[0];
			}
			return view('kalender.edit', compact('agenda'));
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
			$mulai = $request -> mulai3 . '-' . str_pad($request -> mulai2, 2, '0', STR_PAD_LEFT) . '-' . str_pad($request -> mulai1, 2, '0', STR_PAD_LEFT); 
			$request -> merge(['mulai' => $mulai]);
			$sampai = $request -> sampai3 . '-' . str_pad($request -> sampai2, 2, '0', STR_PAD_LEFT) . '-' . str_pad($request -> sampai1, 2, '0', STR_PAD_LEFT); 
			if($sampai != $mulai AND $request -> sampai3 != '') $request -> merge(['sampai' => $sampai]);
			$this -> validate($request, $this->rules);
			
			$input = array_only(Input::all(), ['mulai', 'sampai', 'kegiatan', 'jenis_kegiatan']);
			
			$kalender = Kalender::find($id);
			$kalender -> update($input);
			
			return Redirect::route('kalender.index') -> with('message', 'Data kegiatan berhasil diperbarui.');
		}
		
		/**
			* Remove the specified resource from storage.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function destroy($id)
		{
			$kegiatan = Kalender::find($id);
			$kegiatan -> delete();
			return Redirect::route('kalender.index') -> with('message', 'Data kegiatan berhasil dihapus.');
		}
	}

<?php
	
	namespace Siakad\Http\Controllers;
	
	use Redirect;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Input;
	
	use Siakad\Skripsi;
	use Siakad\Mahasiswa;
	use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	class SkripsiController extends Controller
	{
		protected $rules = [
		'judul' => ['required'],
		];
		
		function __construct()
		{
			$this -> cache = 'skripsi_tmp_' . csrf_token();
		}
		
		public function downloadFile($id) 
		{
			$skripsi = Skripsi::whereId($id) -> first();
			if(!isset($skripsi -> file) or $skripsi -> file == '') abort(404);
			$storage = \Storage::disk('files');	
			if(!$storage -> exists($skripsi -> file)) abort(404);
			$filename = str_slug($skripsi -> judul) . '.' . $skripsi -> ext;
			return \Response::download($storage -> getDriver() -> getAdapter() -> getPathPrefix() . $skripsi -> file, $filename, [$skripsi -> mime]);
		}
		
		public function show($id)
		{
			$skripsi = Skripsi::whereId($id) -> first();
			if(!$skripsi) abort(404);
			return view('mahasiswa.skripsi.show', compact('skripsi'));
		}
		
		/**
			* Search
		**/
		public function search()
		{			
			$query = Input::get('q');
			$skripsi = Skripsi::with('pengarang') -> with('pembimbing') -> search($query) -> orderBy('id', 'desc') -> paginate(30);
			$message = 'Ditemukan ' . $skripsi -> total() . ' hasil pencarian';
			
			return view('mahasiswa.skripsi.index', compact('skripsi', 'message'));
		}
		public function index()
		{
			$skripsi = Skripsi::with('pengarang') -> with('pembimbing') -> orderBy('id', 'desc') -> paginate(30);
			return view('mahasiswa.skripsi.index', compact('skripsi'));
		}
		
		/* public function filter()
			{
			$query = Input::get('query');
			if(isset($query))
			{
			$tmp = [];
			$mahasiswa = Mahasiswa::where('skripsi_id', 0) 
			-> where('semesterMhs', '>=', 7) 
			-> where(function($q) use ($query){
			$q 
			-> where('nama', 'LIKE', '%' . $query . '%') 
			-> orWhere('NIM', 'LIKE', $query . '%') ;
			}) 
			-> orderBy('nama') 
			-> get();
			foreach($mahasiswa as $m) $tmp[] = ['data' => $m -> id, 'value' => $m -> nama . ' - ' . $m -> NIM];
			return \Response::json(['suggestions' => $tmp]);
			}
		} */
		/**
			* Show the form for creating a new resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function create()
		{
			$tmp = \Cache::get($this -> cache);
			$tmp2[0] = '-';
			$dosen = \Siakad\Dosen::lists('nama', 'id');
			$mahasiswa = Mahasiswa::where(function($q){
				$q 
				-> where('skripsi_id', 0) 
				-> orWhereNull('skripsi_id');
			}) 
			-> where('semesterMhs', '>=', 7) 
			-> orderBy('nama') 
			-> get();
			foreach($mahasiswa as $m) $tmp2[$m -> id] = $m -> nama . ' - ' . $m -> NIM;
			$mahasiswa = $tmp2;
			return view('mahasiswa.skripsi.create', compact('tmp', 'dosen', 'mahasiswa'));
		}
		
		public function store_tmp()
		{
			$input = Input::except(['_token']);
			$existing = \Cache::get($this -> cache, []);
			
			$data[$input['mahasiswa_id']] = $input;
			
			$new = $existing + $data;
			\Cache::put($this -> cache, $new, 30);
			return \Response::json($new);
		}
		public function remove_tmp()
		{
			\Cache::forget($this -> cache);
			return Redirect::route('skripsi.create') -> with('success', 'Data berhasil dihapus.');
		}
		public function destroy_tmp($id)
		{
			$existing = \Cache::get($this -> cache);
			$new = array_except($existing, [$id]);
			if(count($new) < 1) $new = [];
			\Cache::put($this -> cache, $new, 30);
			return \Response::json($new);
		}
		
		/**
			* Store a newly created resource in storage.
			*
			// * @param  \Illuminate\Http\Request  $request
			* @return \Illuminate\Http\Response
		*/
		public function store(Request $request)
		{
			$data = \Cache::get($this -> cache);
			$c = $e = 0;
			if(count($data) < 1) return Redirect::route('skripsi.create') -> with('warning', 'Data belum diisi.');
			
			foreach($data as $d)
			{
				$skripsi = Skripsi::create(['judul' => $d['judul']]);
				if($skripsi)
				{
					$mahasiswa = \Siakad\Mahasiswa::find($d['mahasiswa_id']);
					if($mahasiswa)
					{
						$result = $mahasiswa -> update(['skripsi_id' => $skripsi -> id]);	
						if($result)
						{
							
							if($d['dosen1_id'] > 0)
							{
								$bimbingan = \Siakad\DosenSkripsi::create(['dosen_id' => $d['dosen1_id'], 'skripsi_id' => $skripsi -> id]);
								if($bimbingan) $c++;
							}
							
							if($d['dosen2_id'] > 0 && $d['dosen2_id'] != $d['dosen1_id'])
							{
								$bimbingan = \Siakad\DosenSkripsi::create(['dosen_id' => $d['dosen2_id'], 'skripsi_id' => $skripsi -> id]);
								if($bimbingan) $c++;
							}
						}
						else $e++;
					}
					else $e++;
				}
				else $e++;
			}
			\Cache::forget($this -> cache);
			return Redirect::route('skripsi.index') -> with('success', $c . ' data Skripsi berhasil dimasukkan.');
		}
		
		/**
			* Show the form for editing the specified resource.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function edit($id)
		{
			$skripsi = Skripsi::find($id);
			$mahasiswa = $skripsi -> pengarang;
			$pembimbing = $skripsi -> pembimbing;
			
			$dosen = \Siakad\Dosen::lists('nama', 'id');
			
			if(isset($pembimbing[0])) $skripsi -> pembimbing1 = $pembimbing[0] -> id;
			if(isset($pembimbing[1])) $skripsi -> pembimbing2 = $pembimbing[1] -> id;
			
			return view('mahasiswa.skripsi.edit', compact('skripsi', 'mahasiswa', 'dosen'));
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
			$input = Input::except('_method', 'pembimbing1', 'pembimbing2');
			
			$file = $input['softcopy'];
			if(isset($file) and $file != '')
			{
				$softcopy = true;
				$validator = \Validator::make($input, ['softcopy' => 'mimes:pdf,doc,docx']);
				if($validator -> fails())
				{
					$softcopy = false;
				}
				else
				{
					$date = date('Y/m/d/');
					$filename = str_random(9);		
					$storage = \Storage::disk('files');
					$result = $storage -> put($date . $filename, \File::get($file));
					if(!$result)
					{
						$softcopy = false;
					}
					
					$input['file'] = $date . $filename;
					$input['mime'] = $file -> getClientMimeType();
					$input['ext'] = $file -> getClientOriginalExtension();
				}
			}
			unset($input['softcopy']);
			
			$p1 = Input::get('pembimbing1');
			$p2 = Input::get('pembimbing2');
			
			if($p1 > 0)
			{
				\Siakad\DosenSkripsi::create(['dosen_id' => $p1, 'skripsi_id' => $id]);
			}
			
			if($p2 > 0 && $p2 != $p1)
			{
				\Siakad\DosenSkripsi::create(['dosen_id' => $p2, 'skripsi_id' => $id]);
			}
			
			Skripsi::find($id) -> update($input);		
			
			return Redirect::route('skripsi.index') -> with('success', 'Data Skripsi berhasil diperbarui.');
		}
		
		/**
			* Remove the specified resource from storage.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function destroy($id)
		{
			Skripsi::find($id) -> delete();
			\Siakad\Mahasiswa::where('skripsi_id', $id) -> update(['skripsi_id' => 0]);
			return Redirect::route('skripsi.index') -> with('success', 'Data Skripsi berhasil dihapus.');
		}
	}

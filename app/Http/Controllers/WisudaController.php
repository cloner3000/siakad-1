<?php
	
	namespace Siakad\Http\Controllers;
	
	use Cache;
	use Redirect;
	
	use Siakad\Wisuda;
	use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Input;
	
	class WisudaController extends Controller
	{
		protected $rules = [
		'nama' => ['required', 'min:3'],
		'tanggal' => ['required', 'date', 'date_format:d-m-Y'],
		/*'kuota' => ['required', 'numeric'],*/
		];
		
		/*
			15 Okt 2016
			Wisuda
		*/
		
		public function cetakFormulir($id, $mhs)
		{
			$mahasiswa = \Siakad\Mahasiswa::whereId($mhs) -> first();
			$wisuda = Wisuda::whereId($id) -> first();
			$alamat = '';
			if($mahasiswa['jalan'] != '') $alamat .= 'Jl. ' . $mahasiswa['jalan'] . ' ';
			if($mahasiswa['dusun'] != '') $alamat .= $mahasiswa['dusun'] . ' ';
			if($mahasiswa['rt'] != '') $alamat .= 'RT ' . $mahasiswa['rt'] . ' ';
			if($mahasiswa['rw'] != '') $alamat .= 'RW ' . $mahasiswa['rw'] . ' ';
			if($mahasiswa['kelurahan'] != '') $alamat .= $mahasiswa['kelurahan'] . ' ';
			if($mahasiswa['id_wil'] != '') 
			{
				$wilayah2 = \Siakad\Wilayah::dataKecamatan($mahasiswa['id_wil']) -> first();
				$alamat .= trim($wilayah2 -> kec) . ' ' . trim($wilayah2 -> kab) . ' ' . trim($wilayah2 -> prov) . ' ';
			}
			if($mahasiswa['kodePos'] != '') $alamat .= $mahasiswa['kodePos'];
			
			return view('mahasiswa.wisuda.printFormulir', compact('mahasiswa', 'wisuda', 'alamat'));
		}
		
		public function cetakFormulir2()
		{
			$mahasiswa = \Auth::user() -> authable;
			$wisuda = Wisuda::whereId($mahasiswa -> wisuda_id) -> first();
			$alamat = '';
			if($mahasiswa['jalan'] != '') $alamat .= 'Jl. ' . $mahasiswa['jalan'] . ' ';
			if($mahasiswa['dusun'] != '') $alamat .= $mahasiswa['dusun'] . ' ';
			if($mahasiswa['rt'] != '') $alamat .= 'RT ' . $mahasiswa['rt'] . ' ';
			if($mahasiswa['rw'] != '') $alamat .= 'RW ' . $mahasiswa['rw'] . ' ';
			if($mahasiswa['kelurahan'] != '') $alamat .= $mahasiswa['kelurahan'] . ' ';
			if($mahasiswa['id_wil'] != '') 
			{
				$wilayah2 = \Siakad\Wilayah::dataKecamatan($mahasiswa['id_wil']) -> first();
				$alamat .= trim($wilayah2 -> kec) . ' ' . trim($wilayah2 -> kab) . ' ' . trim($wilayah2 -> prov) . ' ';
			}
			if($mahasiswa['kodePos'] != '') $alamat .= $mahasiswa['kodePos'];
			
			return view('mahasiswa.wisuda.printFormulir', compact('mahasiswa', 'wisuda', 'alamat'));
		}
		
		public function formDaftarWisuda()
		{
			$data = $propinsi = null;
			$show = $admin = false;
			
			$wisuda = Wisuda::where('daftar', 'y') -> exists();
			if($wisuda)
			{
				$data = \Auth::user() -> authable;
				
				$wilayah = Cache::get('wilayah', function() {
					$wilayah = \Siakad\Wilayah::kecamatan() -> get();
					$tmp[1] = '';
					foreach($wilayah as $kec)
					{
						$tmp[$kec -> id] = $kec['kec'] . ' - ' . $kec['kab'] . ' - ' . $kec['prov'];
					}
					Cache::put('wilayah', $tmp, 60);
					return $tmp;
				});
				
				$alamat = '';
				if($data['jalan'] != '') $alamat .= 'Jl. ' . $data['jalan'] . ' ';
				if($data['dusun'] != '') $alamat .= $data['dusun'] . ' ';
				if($data['rt'] != '') $alamat .= 'RT ' . $data['rt'] . ' ';
				if($data['rw'] != '') $alamat .= 'RW ' . $data['rw'] . ' ';
				if($data['kelurahan'] != '') $alamat .= $data['kelurahan'] . ' ';
				if($data['id_wil'] != '') 
				{
					$wilayah2 = \Siakad\Wilayah::dataKecamatan($data['id_wil']) -> first();
					$alamat .= trim($wilayah2 -> kec) . ' ' . trim($wilayah2 -> kab) . ' ' . trim($wilayah2 -> prov) . ' ';
				}
				if($data['kodePos'] != '') $alamat .= $data['kodePos'];
				
				$wisuda = Wisuda::whereDaftar('y') -> orderBy('tanggal') -> get();
				foreach($wisuda as $w) $tmp[$w -> id] = $w -> nama . ' (' . formatTanggal(date('Y-m-d', strtotime($w -> tanggal))) . ')';
				$wisuda = $tmp;
				
				if(intval($data -> wisuda_id) > 0) 
				{
					$show = true;	
					$wisuda = Wisuda::whereId($data -> wisuda_id) -> get();
				}
			}
			return view('mahasiswa.wisuda.daftar', compact('data', 'wilayah', 'alamat', 'show', 'wisuda', 'admin'));
		}
		
		public function daftarWisuda(Request $request)
		{
			$rules = [
			'foto' => ['required', 'min:3'],
			'namaAyah' => ['required', 'min:3'],
			'tmpLahir' => ['required', 'min:3'],
			'tglLahir' => ['required', 'date_format:d-m-Y'],
			'hp' => ['required', 'min:10'],
			'kelurahan' => ['required', 'min:3'],
			'dusun' => ['required', 'min:3'],
			'judulSkripsi' => ['required', 'min:10']
			];
			
			$this -> validate($request, $rules);
			$data = \Auth::user() -> authable;
			$input = Input::except('_method');
			
			\Siakad\Mahasiswa::find($data -> id) -> update($input);
			
			return Redirect::route('mahasiswa.wisuda.formdaftar') -> with('message', 'Proses pendaftaran berhasil.');
		}
		
		/**
			* Display a listing of the resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function index()
		{
			$wisuda = Wisuda::dataWisuda() -> get();
			return view('mahasiswa.wisuda.index', compact('wisuda'));
		}
		
		public function peserta($id)
		{
			$wisuda = Wisuda::whereId($id) -> first();
			$peserta = \Siakad\Mahasiswa::where('wisuda_id', $id) -> with('skripsi') -> with('prodi') -> with('kelas') -> paginate(30);
			return view('mahasiswa.wisuda.peserta', compact('wisuda', 'peserta'));
		}
		
		public function showPeserta($id, $mhs)
		{
			$wisuda = Wisuda::whereId($id) -> first();
			$show = $admin = true;
			$data = \Siakad\Mahasiswa::whereId($mhs) -> first();
			
			$alamat = '';
			if($data['jalan'] != '') $alamat .= 'Jl. ' . $data['jalan'] . ' ';
			if($data['dusun'] != '') $alamat .= $data['dusun'] . ' ';
			if($data['rt'] != '') $alamat .= 'RT ' . $data['rt'] . ' ';
			if($data['rw'] != '') $alamat .= 'RW ' . $data['rw'] . ' ';
			if($data['kelurahan'] != '') $alamat .= $data['kelurahan'] . ' ';
			if($data['id_wil'] != '') 
			{
				$wilayah2 = \Siakad\Wilayah::dataKecamatan($data['id_wil']) -> first();
				$alamat .= trim($wilayah2 -> kec) . ' ' . trim($wilayah2 -> kab) . ' ' . trim($wilayah2 -> prov) . ' ';
			}
			if($data['kodePos'] != '') $alamat .= $data['kodePos'];
			
			return view('mahasiswa.wisuda.daftar', compact('data', 'alamat', 'show', 'wisuda', 'admin'));
		}
		
		public function hapusPeserta($id, $mhs)
		{
			\Siakad\Mahasiswa::find($mhs) -> update(['wisuda_id' => '']);
			return Redirect::route('mahasiswa.wisuda.peserta', $id) -> with('message', 'Peserta wisuda berhasil dihapus.');
		}
		
		/**
			* Show the form for creating a new resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function create()
		{
			return view('mahasiswa.wisuda.create');
		}
		
		/**
			* Store a newly created resource in storage.
			*
			* @param  \Illuminate\Http\Request  $request
			* @return \Illuminate\Http\Response
		*/
		public function store(Request  $request)
		{
			$this -> validate($request, $this -> rules);
			$input = Input::all();
			$input['tanggal'] = date('Y-m-d', strtotime($input['tanggal']));
			Wisuda::create($input);
			return Redirect::route('mahasiswa.wisuda.index') -> with('message', 'Jadwal wisuda berhasil disimpan.');
		}
		
		/**
			* Show the form for editing the specified resource.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function edit($id)
		{			
			$wisuda = Wisuda::where('id', $id) -> first();
			return view('mahasiswa.wisuda.edit', compact('wisuda'));
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
			$input = Input::except('_method');
			$input['tanggal'] = date('Y-m-d', strtotime($input['tanggal']));
			Wisuda::find($id) -> update($input);
			
			return Redirect::route('mahasiswa.wisuda.index') -> with('message', 'Jadwal Wisuda berhasil diperbarui.');
		}
		
		/**
			* Remove the specified resource from storage.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function destroy($id)
		{
			Wisuda::find($id) -> delete();
			return Redirect::route('mahasiswa.wisuda.index') -> with('message', 'Jadwal Wisuda berhasil dihapus.');
		}
	}

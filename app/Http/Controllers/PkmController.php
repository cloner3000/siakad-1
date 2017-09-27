<?php
	
	namespace Siakad\Http\Controllers;
	
	use Cache;
	use Redirect;
	
	use Siakad\Pkm;
	use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Input;
	
	class PkmController extends Controller
	{
		protected $rules = [
		'tahun1' => ['required', 'numeric', 'min:2000', 'max:2036'],
		'tahun2' => ['required', 'numeric', 'min:2000', 'max:2036'],
		'tanggal_mulai' => ['required', 'date', 'date_format:d-m-Y'],
		'tanggal_selesai' => ['date', 'date_format:d-m-Y', 'after:tanggal_mulai']
		];
		
		public function cetakFormulir($id, $mhs)
		{
			$mahasiswa = \Siakad\Mahasiswa::whereId($mhs) -> first();
			$pkm = Pkm::whereId($id) -> first();
			if(!$pkm) abort(404);
			$prodi = \Siakad\Prodi::all();
			foreach($prodi as $p) $tmp[] = $p -> nama . '(' . $p -> singkatan . ')';
			$prodi = $tmp;
			
			$alamat = '';
			if($mahasiswa['jalan'] != '') $alamat .= 'Jl. ' . $mahasiswa['jalan'] . ' ';
			if($mahasiswa['dusun'] != '') $alamat .= $mahasiswa['dusun'] . ' ';
			if($mahasiswa['rt'] != '') $alamat .= 'RT ' . $mahasiswa['rt'] . ' ';
			if($mahasiswa['rw'] != '') $alamat .= 'RW ' . $mahasiswa['rw'] . ' ';
			if($mahasiswa['kelurahan'] != '') $alamat .= $mahasiswa['kelurahan'] . ' ';
			if($mahasiswa['wilayah_id'] != '') 
			{
				$wilayah2 = \Siakad\Wilayah::dataKecamatan($mahasiswa['wilayah_id']) -> first();
				$alamat .= trim($wilayah2 -> kec) . ' ' . trim($wilayah2 -> kab) . ' ' . trim($wilayah2 -> prov) . ' ';
			}
			if($mahasiswa['kodePos'] != '') $alamat .= $mahasiswa['kodePos'];
			
			return view('mahasiswa.pkm.printFormulir', compact('mahasiswa', 'pkm', 'prodi', 'alamat'));
		}
		
		public function cetakFormulir2()
		{
			$mahasiswa = \Auth::user() -> authable;
			$pkm = Pkm::whereId($mahasiswa -> pkm_id) -> first();
			if(!$pkm) abort(404);
			$prodi = \Siakad\Prodi::all();
			foreach($prodi as $p) $tmp[] = $p -> nama . '(' . $p -> singkatan . ')';
			$prodi = $tmp;
			
			$alamat = '';
			if($mahasiswa['jalan'] != '') $alamat .= 'Jl. ' . $mahasiswa['jalan'] . ' ';
			if($mahasiswa['dusun'] != '') $alamat .= $mahasiswa['dusun'] . ' ';
			if($mahasiswa['rt'] != '') $alamat .= 'RT ' . $mahasiswa['rt'] . ' ';
			if($mahasiswa['rw'] != '') $alamat .= 'RW ' . $mahasiswa['rw'] . ' ';
			if($mahasiswa['kelurahan'] != '') $alamat .= $mahasiswa['kelurahan'] . ' ';
			if($mahasiswa['wilayah_id'] != '') 
			{
				$wilayah2 = \Siakad\Wilayah::dataKecamatan($mahasiswa['wilayah_id']) -> first();
				$alamat .= trim($wilayah2 -> kec) . ' ' . trim($wilayah2 -> kab) . ' ' . trim($wilayah2 -> prov) . ' ';
			}
			if($mahasiswa['kodePos'] != '') $alamat .= $mahasiswa['kodePos'];
			return view('mahasiswa.pkm.printFormulir', compact('mahasiswa', 'pkm', 'prodi', 'alamat'));
		}
		
		public function formDaftarPkm()
		{
			$data = null;
			$show = $admin = false;
			
			$pkm = Pkm::where('daftar', 'y') -> exists();
			if($pkm)
			{
				$data = \Auth::user() -> authable;
				$negara = Cache::get('negara', function() {
					$negara = \Siakad\Negara::orderBy('nama') -> lists('nama', 'kode');
					Cache::put('negara', $negara, 60);
					return $negara;
				});
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
				if($data['wilayah_id'] != '') 
				{
					$wilayah2 = \Siakad\Wilayah::dataKecamatan($data['wilayah_id']) -> first();
					$alamat .= trim($wilayah2 -> kec) . ' ' . trim($wilayah2 -> kab) . ' ' . trim($wilayah2 -> prov) . ' ';
				}
				if($data['kodePos'] != '') $alamat .= $data['kodePos'];
				
				$pkm = Pkm::whereDaftar('y') -> orderBy('tanggal_mulai') -> get();
				foreach($pkm as $w) $tmp[$w -> id] = $w -> nama . ' (' . formatTanggal(date('Y-m-d', strtotime($w -> tanggal_mulai))) . ' - ' . formatTanggal(date('Y-m-d', strtotime($w -> tanggal_selesai))) . ')';
				$pkm = $tmp;
				
				if(intval($data -> pkm_id) > 0) 
				{
					$show = true;	
					$pkm = Pkm::whereId($data -> pkm_id) -> get();
				}
			}
			return view('mahasiswa.pkm.daftar', compact('data', 'wilayah', 'alamat',  'show', 'pkm', 'admin', 'negara'));
		}
		
		public function daftarPkm(Request $request)
		{
			$rules = [
			'foto' => ['required', 'min:3'],
			'tmpLahir' => ['required', 'min:3'],
			'tglLahir' => ['required', 'date_format:d-m-Y'],
			'hp' => ['required', 'min:10'],
			'kelurahan' => ['required', 'min:3'],
			'dusun' => ['required', 'min:3'],
			];
			
			$this -> validate($request, $rules);
			$data = \Auth::user() -> authable;
			$input = Input::except('_method');
			if(is_array($input['kemampuan']))
			{
				$input['kemampuan'] = implode('[]', $input['kemampuan']);
			}
			if(is_array($input['kekurangan']))
			{
				$input['kekurangan'] = implode('[]', $input['kekurangan']);
			}
			\Siakad\Mahasiswa::find($data -> id) -> update($input);
			
			return Redirect::route('mahasiswa.pkm.formdaftar') -> with('message', 'Proses pendaftaran berhasil.');
		}
		
		/**
			* Display a listing of the resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function index()
		{
			$pkm = Pkm::orderBy('tanggal_mulai') -> get();
			return view('mahasiswa.pkm.index', compact('pkm'));
		}
		
		public function peserta($id)
		{
			$pkm = Pkm::whereId($id) -> first();
			// $peserta = $pkm -> peserta;
			$peserta = \Siakad\Mahasiswa::where('pkm_id', $id) -> with('prodi') -> with('kelas') -> paginate(30);
			return view('mahasiswa.pkm.peserta', compact('pkm', 'peserta'));
		}
		
		public function showPeserta($id, $mhs)
		{
			$pkm = Pkm::whereId($id) -> first();
			$show = $admin = true;
			$data = \Siakad\Mahasiswa::whereId($mhs) -> first();
			
			$alamat = '';
			if($data['jalan'] != '') $alamat .= 'Jl. ' . $data['jalan'] . ' ';
			if($data['dusun'] != '') $alamat .= $data['dusun'] . ' ';
			if($data['rt'] != '') $alamat .= 'RT ' . $data['rt'] . ' ';
			if($data['rw'] != '') $alamat .= 'RW ' . $data['rw'] . ' ';
			if($data['kelurahan'] != '') $alamat .= $data['kelurahan'] . ' ';
			if($data['wilayah_id'] != '') 
			{
				$wilayah2 = \Siakad\Wilayah::dataKecamatan($data['wilayah_id']) -> first();
				$alamat .= trim($wilayah2 -> kec) . ' ' . trim($wilayah2 -> kab) . ' ' . trim($wilayah2 -> prov) . ' ';
			}
			if($data['kodePos'] != '') $alamat .= $data['kodePos'];
			
			return view('mahasiswa.pkm.daftar', compact('data', 'alamat', 'show', 'pkm', 'admin'));
		}
		
		public function hapusPeserta($id, $mhs)
		{
			\Siakad\Mahasiswa::find($mhs) -> update(['pkm_id' => '']);
			return Redirect::route('mahasiswa.pkm.peserta', $id) -> with('message', 'Peserta pkm berhasil dihapus.');
		}
		
		/**
			* Show the form for creating a new resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function create()
		{
		return view('mahasiswa.pkm.create');
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
		$input['nama'] = $input['tahun1'] . '/' . $input['tahun2'];
		unset($input['tahun1']);
		unset($input['tahun2']);
		$input['tanggal_mulai'] = date('Y-m-d', strtotime($input['tanggal_mulai']));
		$input['tanggal_selesai'] = date('Y-m-d', strtotime($input['tanggal_selesai']));
		Pkm::create($input);
		return Redirect::route('mahasiswa.pkm.index') -> with('message', 'Data PKM berhasil disimpan.');
		}
		
		/**
		* Show the form for editing the specified resource.
		*
		* @param  int  $id
		* @return \Illuminate\Http\Response
		*/
		public function edit($id)
		{			
		$pkm = Pkm::where('id', $id) -> first();
		return view('mahasiswa.pkm.edit', compact('pkm'));
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
		$input['nama'] = $input['tahun1'] . '/' . $input['tahun2'];
		unset($input['tahun1']);
		unset($input['tahun2']);
		$input['tanggal_mulai'] = date('Y-m-d', strtotime($input['tanggal_mulai']));
		$input['tanggal_selesai'] = date('Y-m-d', strtotime($input['tanggal_selesai']));
		Pkm::find($id) -> update($input);
		
		return Redirect::route('mahasiswa.pkm.index') -> with('message', 'Data PKM berhasil diperbarui.');
		}
		
		/**
		* Remove the specified resource from storage.
		*
		* @param  int  $id
		* @return \Illuminate\Http\Response
		*/
		public function destroy($id)
		{
		Pkm::find($id) -> delete();
		return Redirect::route('mahasiswa.pkm.index') -> with('message', 'Data PKM berhasil dihapus.');
		}
		}
				
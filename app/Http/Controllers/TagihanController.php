<?php
	
	namespace Siakad\Http\Controllers;
	
	use Redirect;
	
	use Illuminate\Support\Facades\Input;
	use Illuminate\Http\Request;
	
	use Siakad\Biaya;
	use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	class TagihanController extends Controller
	{
		use \Siakad\MahasiswaTrait;
		protected $rules = ['angsuran' => ['required_if:lunas,n', 'numeric']];
		
		/**
			* Tagihan
		**/
		public function index()
		{
			$biaya = Biaya::daftarPembayaran(null, true) -> with('petugas') -> with('tapel') -> paginate(30);
			return view('biaya.tagihan.index', compact('biaya'));
		}
		
		public function create($mahasiswa_id = null)
		{
			$jenis[0.0] = 'Pilih jenis pembayaran';
			foreach(\Siakad\JenisBiaya::orderBy('nama') -> get() as $j) $jenis[$j -> id . '.' . $j -> jangka . '.' . $j -> jumlah] = $j -> nama . ' (Rp. ' . number_format($j -> jumlah, 2, ',', '.') .')';
			
			$mahasiswa = $mahasiswa_id != null ? \Siakad\Mahasiswa::whereId($mahasiswa_id) -> first() : [];
			
			$target = ['-' => 'Pilih sasaran tagihan', 'all' => 'Seluruh Mahasiswa', 'angkatan' => 'Angkatan', 'semester' => 'Semester'];
			$angkatan = $this -> getGolongan('angkatan', false);
			$semester = $this -> getGolongan('semester', false);
			$tapel = \Siakad\Tapel::orderBy('nama') -> lists('nama', 'id');
			$active = \Siakad\Tapel::whereAktif('y') -> pluck('id');
			return view('biaya.tagihan.create', compact('jenis', 'mahasiswa', 'target', 'angkatan', 'semester', 'tapel', 'active'));
		}
		
		public function store()
		{
			$input = Input::except('_token');
			$target = null;
			switch($input['target'])
			{
				case 'all':
				$target = \Siakad\Mahasiswa::all() -> pluck('id');			
				break;
				
				case 'angkatan':
				$target = \Siakad\Mahasiswa::where('NIM', 'LIKE', $input['angkatan'] . '%') -> get() -> pluck('id');
				break;
				
				case 'semester':
				$target = \Siakad\Mahasiswa::where('semesterMhs', $input['semester']) -> get() -> pluck('id');
				break;
			}
			
			if($target == null) return redirect() -> back() -> with('message', 'Data mahasiswa tidak ditemukan');
			if(!$target -> count()) return redirect() -> back() -> with('message', 'Data mahasiswa tidak ditemukan');
			
			$jenis = explode('.', $input['jenis_biaya']);
			$jenis_biaya_id = $jenis[0];
			if($jenis_biaya_id == 0) return redirect() -> back() -> with('warning', 'Silahkan pilih jenis pembayaran');
			
			$periode = '0';
			
			if($jenis[1] == '3') // bulanan
			{
				$periode = $input['p_bulan'] . '-' . $input['p_tahun'];
			}
			
			/* if($jenis[1] == '4') // TA
				{
				$periode = $input['p_ta'];
			} */
			
			$user_id  = \Auth::user() -> id;
			foreach($target as $k => $v) 
			{
				$data[] = '(' . $input['tapel_id'] . ', ' . $jenis_biaya_id . ', ' . '"' . $periode . '", ' . $v . ', ' . '"n", ' . $jenis[2] . ', ' . $user_id . ', now(), now())';
			}
			$result = \DB::insert('INSERT IGNORE INTO `biaya` (`tapel_id`, `jenis_biaya_id`, `periode`, `mahasiswa_id`, `lunas`, `sisa_tanggungan`, `user_id`, `created_at`, `updated_at`) VALUES ' . implode(', ', $data));
			
			return Redirect::route('biaya.tagihan.index') -> with('message', 'Tagihan berhasil disimpan');
		}
		
		/**
			* Query building for search
		**/
		public function preSearch()
		{
			$q = strtolower(Input::get('q'));
			$qclean = preg_replace("[^ 0-9a-zA-Z]", " ", $q);
			
			while (strstr($qclean, "  ")) {
				$qclean = str_replace("  ", " ", $qclean);
			}
			
			$qclean = str_replace(" ", "_", $qclean);
			
			if ($q != '') {
				return redirect( '/biaya/tagihan/search/'.$qclean );
			}
			return Redirect::back() -> withErrors(['q' => 'Isi kata kunci pencarian yang diinginkan terlebih dahulu']);
		}
		
		/**
			* Search
		**/
		public function search($query)
		{			
			$query =str_replace('_', ' ', $query);
			$biaya = Biaya::search($query) -> daftarPembayaran(null, true) -> with('petugas') -> paginate(20);
			
			$message = 'Ditemukan ' . $biaya -> total() . ' hasil pencarian';
			$tapel = \Siakad\Tapel::orderBy('nama') -> lists('nama', 'id');
			return view('biaya.tagihan.index', compact('message', 'biaya', 'tapel'));
		}
		
	}

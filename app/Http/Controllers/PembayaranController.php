<?php
	
	namespace Siakad\Http\Controllers;
	
	use Redirect;
	
	use Illuminate\Support\Facades\Input;
	use Illuminate\Http\Request;
	
	use Siakad\Biaya;
	use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	class PembayaranController extends Controller
	{
		use \Siakad\MahasiswaTrait;
		public function index()
		{
			$biaya = Biaya::daftarPembayaran(\Auth::user() -> authable -> id) -> with('petugas') -> paginate(30);
			$tapel = \Siakad\Tapel::orderBy('nama') -> lists('nama', 'id');
			return view('biaya.pembayaran.index', compact('biaya', 'tapel'));
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
			
			if ($q !== '') {
				return redirect( '/pembayaran/search/'.$qclean );
			}
			
			return Redirect::back() -> withErrors(['q' => 'Isi kata kunci pencarian yang diinginkan terlebih dahulu']);
		}
		
		/**
			* Search
		**/
		public function search($query)
		{			
			$query =str_replace('_', ' ', $query);
			$biaya = Biaya::search($query) -> daftarPembayaran(\Auth::user() -> authable -> id) -> with('petugas') -> paginate(20);
			
			$message = 'Ditemukan ' . $biaya -> total() . ' hasil pencarian';
			$tapel = \Siakad\Tapel::orderBy('nama') -> lists('nama', 'id');
			return view('biaya.pembayaran.index', compact('message', 'biaya', 'tapel'));
		}
	}

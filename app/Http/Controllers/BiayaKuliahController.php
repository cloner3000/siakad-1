<?php
	
	namespace Siakad\Http\Controllers;
	
	use Redirect;
	
	use Illuminate\Support\Facades\Input;
	use Illuminate\Http\Request;
	
	use Siakad\BiayaKuliah;
	
	use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	class BiayaKuliahController extends Controller
	{
		
		public function setup($tahun = null, $prodi_id = 1, $program_id = 1, $jenisPembayaran = 1)
		{
			$data['angkatan'] = $tahun;
			$data['prodi_id'] = $prodi_id;
			$data['program_id'] = $program_id;
			$data['jenisPembayaran'] = $jenisPembayaran;
			
			$angkatan = \DB::select('SELECT DISTINCT LEFT(`nim`, 4) AS `tahun` FROM `mahasiswa` ORDER BY `tahun`');
			foreach($angkatan as $a) $tmp[$a -> tahun] = $a -> tahun;
			$angkatan = $tmp;
			if($tahun == null) $data['angkatan'] = array_values($tmp)[0];
			
			\DB::insert('
			INSERT IGNORE INTO `setup_biaya` (jenis_biaya_id, angkatan, jumlah, prodi_id, kelas_id, jenisPembayaran)
			SELECT `id`, ' . $data['angkatan'] . ', 0, ' . $data['prodi_id'] . ', ' . $data['program_id'] . ', ' . $data['jenisPembayaran'] . '
			FROM `jenis_biaya`
			');
			$jenis = \Siakad\JenisBiaya::biayaKuliah($data) -> get();
			
			$prodi = \Siakad\Prodi::orderBy('nama') -> lists('nama', 'id');
			$program = \Siakad\Kelas::orderBy('nama') -> lists('nama', 'id');
			return view('biaya.setup', compact('jenis', 'angkatan', 'prodi', 'program', 'data'));
		}
		
		public function setupSubmit()
		{
			$input = Input::all();
			$c = $total = 0;
			$saved = false;
			foreach($input['jenis_biaya'] as $j) 
			{
				if($jumlah = intval($input['jumlah'][$c]) != 0)
				{
					$biaya = BiayaKuliah::where('jenis_biaya_id', $j) 
					-> where('angkatan', $input['angkatan']) 
					-> where('prodi_id', $input['prodi_id'])
					-> where('kelas_id', $input['program_id'])
					-> where('jenisPembayaran', $input['jenisPembayaran'])
					-> update(['jumlah' => intval($input['jumlah'][$c])]);
					if($biaya) $saved = true;
					$total += $input['jumlah'][$c];
				}
				$c++;
			}
			if($saved)
			return \Response::json(['success' => true, 'total' => number_format($total, 0, ',', '.')]);
			else
			return \Response::json(['success' => false, 'total' => number_format($total, 0, ',', '.')]);
			// return Redirect::route('biayakuliah.setup') -> with('success', 'Biaya Kuliah berhasil disimpan');
		}
		
	}

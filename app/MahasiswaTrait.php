<?php
	
	namespace Siakad;
	
	use Illuminate\Support\Facades\Input;
	trait MahasiswaTrait{
		public function getGolongan($modifier, $add_pilih = true, $singkatan=false)
		{
			$data = [];
			if($modifier == 'status')
			{
				if($add_pilih) $data['-'] = '-- Status --';
				foreach(config('custom.pilihan.statusMhs') as $k => $v) $data[$k] = $v;
			}
			
			if($modifier == 'prodi')
			{
				$prodi = \Siakad\Prodi::all();
				if($add_pilih) $data['-'] = '-- PRODI --';
				if(!$singkatan) foreach($prodi as $k) $data[$k -> id] = $k -> nama;
				else foreach($prodi as $k) $data[$k -> id] = $k -> singkatan;
			}
			
			if($modifier == 'program')
			{
				$kelas = \Siakad\Kelas::all();
				if($add_pilih) $data['-'] = '-- Program --';
				foreach($kelas as $k) $data[$k -> id] = $k -> nama;
			}
			
			if($modifier == 'angkatan')
			{
				$angkatan = \DB::select('
				SELECT DISTINCT angkatan AS `tahun` FROM `mahasiswa` WHERE statusMhs=1 ORDER BY tahun
				');
				if($add_pilih) $data['-'] = '-- Angkatan --';
				foreach($angkatan as $k => $v) $data[$v->tahun] = $v->tahun;
			}
			
			if($modifier == 'semester')
			{
				if($add_pilih) $data['-'] = '-- Semester --';
				foreach(range(1, 8) as $r) $data[$r] = $r;
			}
			return $data;
		}
	}

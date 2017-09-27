<?php
	
	namespace Siakad\Http\Controllers;
	
	use Redirect;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Input;
	
	use Siakad\MatkulTapel;
	use Siakad\Mahasiswa;
	use Siakad\Tapel;
	use Siakad\Krs;
	use Siakad\KrsDetail;
	use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	class KrsController extends Controller
	{		
		public function printKrs()
		{
			$tapel = Tapel::whereAktif('y') -> first();
			$mhs = Mahasiswa::with('prodi') -> with('kelas') -> with('dosenwali') -> whereId(\Auth::user() -> authable -> id) -> first();
			$krs = Krs::getKRS($mhs -> id, $tapel -> id) -> get();
			/*
				$krs = \DB::select(
				"
				SELECT
				`krs`.`id` as `krs_id`,
				`matkul`.`nama` as `nama_matkul`,	`matkul`.`kode`, matkul.sks_total as sks,
				`matkul_tapel`.`id` as `matkul_tapel_id`,
				`matkul_tapel`.*,
				jadwal.*, 
				dosen.nama as dosen
				FROM `krs`
				inner join `tapel` on `tapel`.`id` = `krs`.`tapel_id`
				inner join `krs_detail` on `krs`.`id` = `krs_detail`.`krs_id`
				inner join `matkul_tapel` on `matkul_tapel`.`id` = `krs_detail`.`matkul_tapel_id`
				inner join `kurikulum_matkul` on `kurikulum_matkul`.`id` = `matkul_tapel`.`kurikulum_matkul_id`
				inner join `matkul` on `matkul`.`id` = `kurikulum_matkul`.`matkul_id`
				inner join `dosen` on `dosen`.`id` = `matkul_tapel`.`dosen_id`
				LEFT join `jadwal` on jadwal.matkul_tapel_id = `matkul_tapel`.`id`
				where `krs`.`mahasiswa_id` = :id AND tapel.id = :tapel_id
				group by matkul.nama;
				", ['id' => $mhs -> id, 'tapel_id' => $tapel -> id]
				);
			*/
			$status = Krs::where('mahasiswa_id', $mhs -> id) -> where('tapel_id', $tapel -> id) -> first();
			return view('krs.print', compact('mhs', 'tapel', 'krs', 'status'));
		}
		public function adminKrs($nim, $action = null)
		{
			$tapel = Tapel::whereAktif('y') -> first();
			$mhs = Mahasiswa::where('NIM', $nim) -> first();
			$status = Krs::firstOrCreate(['mahasiswa_id' => $mhs -> id, 'tapel_id' => $tapel -> id]);
			
			if($action == 'approve') 
			{
				Krs::where('mahasiswa_id', $mhs -> id) -> where('tapel_id', $tapel -> id) -> update(['approved' => 'y']);
			}
			elseif($action == 'review') 
			{
				Krs::where('mahasiswa_id', $mhs -> id) -> where('tapel_id', $tapel -> id) -> update(['approved' => 'n']);
			}
			elseif($action == 'print')
			{
				$krs = Krs::getKRS($mhs -> id, $tapel -> id) -> get();
				/*
					$krs = \DB::select(
					"
					SELECT
					`krs`.`id` as `krs_id`,
					`matkul`.`nama` as `nama_matkul`,	`matkul`.`kode`,	`matkul_tapel`.`id` as `matkul_tapel_id`, matkul.sks_total as sks,
					`matkul_tapel`.*,
					jadwal.*, 
					dosen.nama as dosen
					FROM `krs`
					inner join `tapel` on `tapel`.`id` = `krs`.`tapel_id`
					inner join `krs_detail` on `krs`.`id` = `krs_detail`.`krs_id`
					inner join `matkul_tapel` on `matkul_tapel`.`id` = `krs_detail`.`matkul_tapel_id`
					inner join `kurikulum_matkul` on `kurikulum_matkul`.`id` = `matkul_tapel`.`kurikulum_matkul_id`
					inner join `matkul` on `matkul`.`id` = `kurikulum_matkul`.`matkul_id`
					inner join `dosen` on `dosen`.`id` = `matkul_tapel`.`dosen_id`
					LEFT join `jadwal` on jadwal.matkul_tapel_id = `matkul_tapel`.`id`
					where `krs`.`mahasiswa_id` = :id AND tapel.id = :tapel_id
					group by matkul.nama;
					", ['id' => $mhs -> id, 'tapel_id' => $tapel -> id]
					);
				*/
				return view('krs.print', compact('mhs', 'tapel', 'krs', 'status'));
			}
			
			// $status = Krs::where('mahasiswa_id', $mhs -> id) -> where('tapel_id', $tapel -> id) -> first();
			$krs = Krs::getKRS($mhs -> id, $tapel -> id) -> get();
			/*
				$krs = \DB::select(
				"
				select
				`krs`.`id` as `krs_id`,
				`matkul`.`nama` as `nama_matkul`, `matkul`.`kode`, `matkul`.`sks_total` as `sks`,
				`matkul_tapel`.`id` as `mtid`,
				(select count(krs_detail.matkul_tapel_id) from krs_detail where matkul_tapel_id = mtid) peserta,
				`matkul_tapel`.*,
				jadwal.*,
				ruang.nama as ruangan,
				dosen.nama as dosen
				from `krs`
				inner join `tapel` on `tapel`.`id` = `krs`.`tapel_id`
				inner join `krs_detail` on `krs`.`id` = `krs_detail`.`krs_id`
				inner join `matkul_tapel` on `matkul_tapel`.`id` = `krs_detail`.`matkul_tapel_id`
				inner join `kurikulum_matkul` on `kurikulum_matkul`.`id` = `matkul_tapel`.`kurikulum_matkul_id`
				inner join `matkul` on `matkul`.`id` = `kurikulum_matkul`.`matkul_id`
				inner join `dosen` on `dosen`.`id` = `matkul_tapel`.`dosen_id`
				LEFT join `jadwal` on jadwal.matkul_tapel_id = `matkul_tapel`.`id`
				LEFT join `ruang` on ruang_id = `ruang`.`id`
				where `krs`.`mahasiswa_id` = :id AND tapel.id = :tapel_id
				group by matkul.nama;
				", ['id' => $mhs -> id, 'tapel_id' => $tapel -> id]
				);
			*/
			$sksm = \DB::select("
			select km.semester, sum(m.sks_total) as sksn from nilai n
			left join matkul_tapel mt on n.matkul_tapel_id = mt.id
			left join kurikulum_matkul km on km.id = mt.kurikulum_matkul_id
			left join matkul m on km.matkul_id = m.id
			where n.jenis_nilai_id = 0 AND n.mahasiswa_id = :id
			group by km.semester
			", ['id' => $mhs -> id]);
			
			$nilaim = \DB::select("
			select nilai, count(*) as jumlah from nilai
			where jenis_nilai_id = 0 AND mahasiswa_id = :id AND nilai IS NOT NULL AND nilai <> ''
			group by nilai
			", ['id' => $mhs -> id]);
			
			$ip = \DB::select("
			select km.semester, n.nilai, m.sks_total as sks from nilai n
			left join matkul_tapel mt on n.matkul_tapel_id = mt.id
			left join kurikulum_matkul km on km.id = mt.kurikulum_matkul_id
			left join matkul m on km.matkul_id = m.id
			where n.jenis_nilai_id = 0 AND n.mahasiswa_id = :id
			order by km.semester
			", ['id' => $mhs -> id]);
			
			$konv = config('custom.konversi_nilai.base_4');
			foreach($ip as $i)
			{
				if(!isset($tmp[$i -> semester]['sksn'])) $tmp[$i -> semester]['sksn'] = 0;
				if(!isset($tmp[$i -> semester]['jsks'])) $tmp[$i -> semester]['jsks'] = 0;
				if(isset($konv[$i -> nilai]))
				{
					$tmp[$i -> semester]['sksn'] += $konv[$i -> nilai] * $i -> sks;
				}
				$tmp[$i -> semester]['jsks'] += $i -> sks;				
			}
			$tmp2 = [];
			if(isset($tmp))
			{
				foreach($tmp as $smt => $x)
				{
					$tmp2[$smt] = $x['jsks'] < 1 ? 0: number_format($x['sksn'] / $x['jsks'], 2);
				}
			}
			$ipm = $tmp2;
			
			return view('krs.admin', compact('krs', 'mhs', 'tapel', 'status', 'sksm', 'nilaim', 'ipm'));	
		}
		
		public function index()
		{
			$open = true;
			//if(!\Auth::check()) return redirect('/auth/login');	
			$tapel = Tapel::whereAktif('y') -> first();
			$mhs = Mahasiswa::with('prodi') -> with('kelas') -> whereId(\Auth::user() -> authable -> id) -> first();
			$status = Krs::where('mahasiswa_id', $mhs -> id) -> where('tapel_id', $tapel -> id) -> first();
			if(strtotime(date('Y-m-d ') . '23:59:59') > strtotime($tapel -> selesaiKrs . '23:59:59')) $open = false;
			$krs = Krs::firstOrCreate(['mahasiswa_id' => $mhs -> id, 'tapel_id' => $tapel -> id]);
			
			//$krs = Krs::getKRS($mhs -> id, $tapel -> id) -> get();
			
			$krs = \DB::select(
			"
			select
			`krs`.`id` as `krs_id`,
			`matkul`.`nama` as `nama_matkul`, `matkul`.`kode`, matkul.sks_total as sks,
			`matkul_tapel`.`id` as `mtid`, 
			(select count(krs_detail.matkul_tapel_id) from krs_detail where matkul_tapel_id = mtid) peserta,
			`matkul_tapel`.*
			from `krs`
			inner join `tapel` on `tapel`.`id` = `krs`.`tapel_id`
			inner join `krs_detail` on `krs`.`id` = `krs_detail`.`krs_id`
			inner join `matkul_tapel` on `matkul_tapel`.`id` = `krs_detail`.`matkul_tapel_id`
			inner join `kurikulum_matkul` on `kurikulum_matkul`.`id` = `matkul_tapel`.`kurikulum_matkul_id`
			inner join `matkul` on `matkul`.`id` = `kurikulum_matkul`.`matkul_id`
			where `krs`.`mahasiswa_id` = :id AND tapel.id = :tapel_id;
			", ['id' => $mhs -> id, 'tapel_id' => $tapel -> id]
			);
			
			return view('krs.index', compact('mhs', 'krs', 'tapel', 'status', 'open'));	
		}
		
		/**
			* Show the form for creating a new resource.
			*
			* @return \Illuminate\Http\Response
		*/
		// TAWARAN MATKUL
		public function create()
		{
			$open = true;
			if(!\Auth::check()) return redirect('/auth/login');	
			$mhs = Mahasiswa::with('dosenwali') -> whereId(\Auth::user() -> authable -> id) -> first();
			$tapel = Tapel::whereAktif('y') -> first();
			if(strtotime(date('Y-m-d ') . '23:59:59') > strtotime($tapel -> selesaiKrs . '23:59:59')) $open = false;
			$krs = Krs::firstOrCreate(['mahasiswa_id' => $mhs -> id, 'tapel_id' => $tapel -> id]);
			$matkul = \DB::select("
			SELECT
			matkul.kode,
			matkul.nama AS nama_matkul,
			matkul_tapel.id AS mtid,
			(SELECT COUNT(krs_detail.matkul_tapel_id) FROM krs_detail WHERE matkul_tapel_id = mtid) peserta,
			prodi.singkatan AS nama_prodi,
			kelas.nama AS program,
			kurikulum_matkul.semester,
			matkul.sks_total as sks, 
			matkul_tapel.kelas2, matkul_tapel.kuota,
			tapel.id AS tapel_id,
			dosen.nama as dosen
			FROM matkul_tapel
			LEFT JOIN `krs_detail` on `krs_detail`.`matkul_tapel_id` = `matkul_tapel`.`id`
			LEFT JOIN `krs` on `krs_detail`.`krs_id` = `krs`.`id`
			INNER JOIN `dosen` on `matkul_tapel`.`dosen_id` = `dosen`.`id`
			INNER JOIN `tapel` on `matkul_tapel`.`tapel_id` = `tapel`.`id`
			INNER JOIN `kurikulum_matkul` on `kurikulum_matkul`.`id` = `matkul_tapel`.`kurikulum_matkul_id`
			INNER JOIN `matkul` on `matkul`.`id` = `kurikulum_matkul`.`matkul_id`
			INNER JOIN `prodi` on `matkul_tapel`.`prodi_id` = `prodi`.`id`
			INNER JOIN `kelas` on `matkul_tapel`.`kelas` = `kelas`.`id`
			WHERE `matkul_tapel`.`prodi_id` = :prodi 
			AND matkul_tapel.id NOT IN (SELECT matkul_tapel_id FROM krs_detail JOIN krs ON krs.id = krs_detail.krs_id  WHERE krs.mahasiswa_id = :id)
			AND matkul.id NOT IN (
			SELECT matkul.id 
			FROM matkul 
			JOIN `kurikulum_matkul` on `kurikulum_matkul`.`matkul_id` = `matkul`.`id`
			JOIN matkul_tapel ON `kurikulum_matkul`.`id` = `matkul_tapel`.`kurikulum_matkul_id`
			JOIN krs_detail ON krs_detail.matkul_tapel_id = matkul_tapel.id
			JOIN krs ON krs.id = krs_detail.krs_id 
			WHERE krs.mahasiswa_id = :id2
			)
			AND `kelas` = :program
			AND `semester` = :semester 
			AND `tapel`.`aktif` = 'y'
			GROUP BY `matkul_tapel`.`id`
			",
			['prodi' => $mhs -> prodi_id, 'program' => $mhs -> kelasMhs, 'semester' => $mhs -> semesterMhs, 'id' => $mhs -> id, 'id2' => $mhs -> id]);
			return view('krs.tawaran', compact('matkul', 'mhs', 'krs', 'open'));
		}
		
		/**
			* Store a newly created resource in storage.
			*
			// * @param  \Illuminate\Http\Request  $request
			* @return \Illuminate\Http\Response
		*/
		public function store()
		{
			$input= Input::except('_token');
			if(!isset($input['matkul_tapel_id'])) return Redirect::back() -> with('warning', 'Mata Kuliah belum dipilih.');
			
			foreach($input['matkul_tapel_id'] as $id) 
			{
				//$data[] = ['krs_id' => $input['krs_id'], 'matkul_tapel_id' => $id];
				$data[] = '(' . $input['krs_id'] . ', ' . $id .')';
				$nilai[] = '(' . $id . ', ' . $input['mahasiswa_id'] . ', 0)';
			}
			//KrsDetail::insert($data);		
			
			\DB::insert('INSERT IGNORE INTO krs_detail (krs_id, matkul_tapel_id) VALUES ' . implode(', ', $data));
			//insert into nilai
			\DB::insert('INSERT IGNORE INTO `nilai` (`matkul_tapel_id`, `mahasiswa_id`, `jenis_nilai_id`) VALUES ' . implode(', ', $nilai));
			return Redirect::route('krs.index') -> with('message', 'KRS berhasil disimpan.');
		}
		
		/**
			* Show the form for editing the specified resource.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		/* public function edit($id)
			{
			$krs = Krs::find($id);
			return view('krs.edit', compact('krs'));
		} */
		
		/**
			* Update the specified resource in storage.
			*
			// * @param  \Illuminate\Http\Request  $request
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		// public function update($id)
		// {
		// $input = Input::except('_method');
		// Krs::find($id) -> update($input);			
		// return Redirect::route('krs.index') -> with('message', 'KRS berhasil diperbarui.');
		// }
		
		/**
			* Remove the specified resource from storage.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function destroy()
		{
			$input = Input::except('_method');
			if(!isset($input['matkul_tapel_id'])) return Redirect::back() -> with('warning', 'Mata Kuliah belum dipilih.');
			foreach($input['matkul_tapel_id'] as $id) KrsDetail::where('krs_id', $input['krs_id']) -> where('matkul_tapel_id', $id) -> delete();
			
			\DB::delete('DELETE FROM `nilai` WHERE `mahasiswa_id` = ' . $input['mahasiswa_id'] . ' AND `matkul_tapel_id` IN ('. implode(',', $input['matkul_tapel_id'] ) .')');
			return Redirect::route('krs.index') -> with('message', 'Mata Kuliah berhasil dihapus.');
		}
	}

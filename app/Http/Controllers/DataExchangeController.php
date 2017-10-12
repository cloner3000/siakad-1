<?php
	
	namespace Siakad\Http\Controllers;
	
	// use Cache;
	use Excel;
	use Redirect;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Input;
	
	use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	class DataExchangeController extends Controller
	{		
		protected $exportable = [
		'kurikulum' => 'Kurikulum',
		'mahasiswa' => 'Mahasiswa',
		'kelas' => 'Kelas Kuliah',
		'krs' => 'KRS',
		'dosen' => 'Dosen Ajar',
		'nilai' => 'Nilai Perkuliahan',
		'akm' => 'AKM',
		'kelulusan' => 'Kelulusan',
		'transkrip_merge' => 'Transkrip Merge'
		];
		
		protected $hasTASelection = ['kelas', 'krs', 'dosen', 'nilai', 'akm', 'kelulusan'];
		
		protected $exportableWarning = [
		'akm' => 'Harap melengkapi data Nilai Mahasiswa untuk mendapatkan data yang akurat.',
		];
		
		public function importYudisiumForm()
		{
			return view('dataexchange.import_yudisium');					
		}
		public function importYudisium()
		{
			$input = Input::all();
			$file = $input['excel'];
			
			$ext = strtolower($file -> getClientOriginalExtension());
			$allowed_exts = ['xls', 'xlsx'];
			if(!in_array($ext, $allowed_exts))
			{
				return Redirect::back() -> withErrors(['error' => 'File yang diperbolehkan adalah: XLS dan XLSX']);
			}
			else
			{
				$date = date('Y/m/d/');
				$filename = str_random(7) . '.' . $ext;		
				$size = $file->getClientSize();
				$storage = \Storage::disk('files');
				$result = $storage -> put($date . $filename, \File::get($file));
				
				if($result)
				{
					$excel = Excel::selectSheetsByIndex(0) -> load($storage->getDriver() -> getAdapter() -> getPathPrefix() . $date . $filename, function($reader) {});
					if(!$excel) Redirect::back() -> withErrors(['error' => 'File tidak dapat dibaca']);
					
					$fail = [];
					$success = 0;
					$failed = 0;
					
					$data = $excel -> formatDates(true, 'd-m-Y') -> get();
					foreach($data as $item)
					{
						$tmp[$item['sk_yudisium']][] = [
						'sk_yudisium' => $item['sk_yudisium'],
						'tgl_sk_yudisium' => $item['tgl_sk_yudisium'],
						'nim' => $item['nim'],
						'tgl_lulus' => $item['tgl_lulus'],
						'no_ijasah' => $item['no_ijasah'],						
						'judul_skripsi' => $item['judul_skripsi']		
						];
					}
					
					$wisuda = false;
					$skripsi = false;
					foreach($tmp as $yd)
					{
						foreach($yd as $ws)
						{
							if($ws['sk_yudisium'] != '')
							{
								if($wisuda == false)
								{
									$wisuda = \Siakad\Wisuda::where('SKYudisium', $ws['sk_yudisium']) -> first();
									if($wisuda == null)
									{
										$tgl = date('Y-m-d', strtotime($ws['tgl_sk_yudisium']));
										$wisuda = \Siakad\Wisuda::create(
										[
										'nama' => $ws['sk_yudisium'],
										'tanggal' => $tgl,
										'SKYudisium' => $ws['sk_yudisium'],
										'tglSKYudisium' => $ws['tgl_sk_yudisium']
										]							
										);
									}
								}
								
								$skripsi = \Siakad\Skripsi::whereJudul(trim($ws['judul_skripsi'])) -> first();
								if($skripsi == null)
								{
									$skripsi = \Siakad\Skripsi::create([
									'judul' => trim($ws['judul_skripsi'])
									]);
								}
								
								$nim = preg_replace('/[^0-9\.]/', '', $ws['nim']);
								$mhs = \Siakad\Mahasiswa::where('NIM', $nim) -> first();
								if($mhs == null)
								{
									$failed ++;
									$fail[] = $nim;		
								}
								else
								{
									$proses = $mhs -> update(
									[
									'wisuda_id' => $wisuda -> id,
									'skripsi_id' => $skripsi -> id,
									'noIjazah' => $ws['no_ijasah'],
									'tglIjazah' => $ws['tgl_lulus'],
									'tglKeluar' => $ws['tgl_lulus']
									]
									);
									if($proses)
									{
										$success++;
									}
									else
									{
										$failed ++;
										$fail[] = $nim;		
									}
								}
							}
						}
						$wisuda = false;
					}
					//delete file
					$storage -> delete($date . $filename);
					
					if($success == 0) return Redirect::route('mahasiswa.yudisium.import') -> withErrors(['error' => 'Impor gagal. ' . $failed . ' data gagal diproses. Pastikan semua data sudah diisi dengan benar,']);
					
					if($success > 0)
					{
						if($failed == 0) return Redirect::route('mahasiswa.yudisium.import') -> with('success', 'Impor sukses. ' . $success . ' data berhasil dimasukkan.');
						return Redirect::route('mahasiswa.yudisium.import') -> with('warning_raw', '<strong>' . $success . '</strong> data berhasil dimasukkan.<br/><strong>' . $failed . '</strong> data gagal diproses.<br/>(' . implode(', ', $fail) . ')');
					}
				}
				return Redirect::route('mahasiswa.yudisium.import') -> withErrors(['error' => 'Upload file gagal']);
			}
		}		
		
		public function importForm()
		{
			$prodi = \Siakad\Prodi::all();
			return view('dataexchange.import', compact('prodi'));		
		}
		
		public function import()
		{
			$input = Input::all();
			$file = $input['excel'];
			$prodi_id = $input['prodi_id'];
			
			//$validator = \Validator::make($input, ['excel' => 'mimes:application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']);
			//$validator = \Validator::make($input, ['excel' => 'mimes:xls,xlsx']);
			//if($validator -> fails())
			
			$ext = strtolower($file -> getClientOriginalExtension());
			$allowed_exts = ['xls', 'xlsx'];
			if(!in_array($ext, $allowed_exts))
			{
				return Redirect::back() -> withErrors(['error' => 'File yang diperbolehkan adalah: XLS dan XLSX']);
			}
			else
			{
				$date = date('Y/m/d/');
				$filename = str_random(7);			
				
				$filename = str_slug($filename) . '.' . $file->getClientOriginalExtension();		
				$size = $file->getClientSize();
				$storage = \Storage::disk('files');
				$result = $storage -> put($date . $filename, \File::get($file));
				
				if($result)
				{
					$excel = Excel::selectSheetsByIndex(0) -> load($storage->getDriver()->getAdapter()->getPathPrefix() . $date . $filename, function($reader) {});
					if(!$excel) Redirect::back() -> withErrors(['error' => 'File tidak dapat dibaca']);
					
					$fail = [];
					$success = 0;
					$failed = 0;
					
					$result = $excel -> formatDates(true, 'd-m-Y') -> get();
					$password = str_random(6);
					if(!isset($result -> first() -> tempat_lahir)) return Redirect::back() -> withErrors(['error' => 'Terjadi kesalahan. Pastikan anda telah men-download template yang telah disediakan dan meng-upload file yang benar']);
					foreach($result as $item)
					{
						if($item['nim'] == '') continue;
						$data = [
						'NIM' => '' . $item['nim'],
						'prodi_id' => $prodi_id,
						'nama' => $item['nama'],
						'tmpLahir' => $item['tempat_lahir'],
						'tglLahir' => $item['tanggal_lahir'] == '-' ? NULL : $item['tanggal_lahir'],
						'jenisKelamin' => strtoupper($item['jenis_kelamin']),
						'NIK' => $item['nik'],
						'agama' => intval($item['agama']),
						'NISN' => $item['nisn'],
						'jalurMasuk' => intval($item['id_jalur_masuk']),
						'NPWP' => $item['npwp'],
						'wargaNegara' => $item['kewarganegaraan'],
						'jenisPendaftaran' => intval($item['jenis_pendaftaran']),
						'tglMasuk' => $item['tgl_masuk_kuliah'] == '-' ? NULL : $item['tgl_masuk_kuliah'],
						'tapelMasuk' => intval($item['mulai_semester']),
						'jalan' => $item['jalan'],
						'rt' => $item['rt'],
						'rw' => $item['rw'],
						'dusun' => isset($item['dusun_lingkungan']) ? $item['dusun_lingkungan'] : $item['nama_dusun'],
						'kelurahan' => isset($item['desa_kelurahan']) ? $item['desa_kelurahan'] : $item['kelurahan'],
						'id_wil' => $item['kecamatan'],
						'kodePos' => '' . $item['kode_pos'],
						'mukim' => intval($item['jenis_tinggal']),
						'transportasi' => intval($item['alat_transportasi']),
						'telp' => $item['telp_rumah'],
						'hp' => $item['no_hp'],
						'email' => $item['email'],
						'kps' => strtoupper($item['terima_kps']),
						'noKps' => $item['no_kps'],
						
						'NIKAyah' => $item['nik_ayah'],
						'namaAyah' => $item['nama_ayah'],
						'tglLahirAyah' => $item['tgl_lahir_ayah'],
						'pendidikanAyah' => intval($item['pendidikan_ayah']),
						'pekerjaanAyah' => intval($item['pekerjaan_ayah']),
						'penghasilanAyah' => intval($item['penghasilan_ayah']),
						
						'NIKIbu' => $item['nik_ibu'],
						'namaIbu' => '' . $item['nama_ibu'],
						'tglLahirIbu' => $item['tanggal_lahir_ibu'],
						'pendidikanIbu' => intval($item['pendidikan_ibu']),
						'pekerjaanIbu' => intval($item['pekerjaan_ibu']),
						'penghasilanIbu' => intval($item['penghasilan_ibu']),
						
						'namaWali' => $item['nama_wali'],
						'tglLahirWali' => $item['tanggal_lahir_wali'],
						'pendidikanWali' => intval($item['pendidikan_wali']),
						'pekerjaanWali' => intval($item['pekerjaan_wali']),
						'penghasilanWali' => intval($item['penghasilan_wali'])
						];
						
						if(\Siakad\Mahasiswa::where('NIM', $data['NIM']) -> exists())
						{
							$failed ++;
							$fail[] = ['NIM' => $data['NIM'], 'nama' => $data['nama']];		
						}
						else
						{
							$result = \Siakad\Mahasiswa::create($data);
							if($result)
							{
								if(!\Siakad\User::where('username', $data['NIM']) -> exists())
								{
									//USER
									$authinfo['username'] = $data['NIM'];
									$authinfo['password'] = bcrypt($password);
									$authinfo['role_id'] = 512;
									$authinfo['authable_id'] = $result -> id;
									$authinfo['authable_type'] = 'Siakad\Mahasiswa';
									\Siakad\User::create($authinfo);
								}
								
								if(!\Siakad\SenayanMembership::where('member_id', $data['NIM']) -> exists())
								{
									//SENAYAN
									$gender = isset($data['jenisKelamin']) AND $data['jenisKelamin'] == 'L' ? 1 : 0;
									$today = date('Y-m-d');
									
									if($data['tglLahir'] != NULL)
									{
										$tmp = explode('-', $data['tglLahir']);
										if(!$tmp) $birth_date = '';
										else $birth_date = $tmp[2] . '-' . $tmp[1] . '-' . $tmp[0];
									}
									else
									{
										$birth_date = '';
									}
									/*
										$address = '';
										if($input['jalan'] != '') $address .= 'Jl. ' . $input['jalan'] . ' ';
										if($input['dusun'] != '') $address .= $input['dusun'] . ' ';
										if($input['rt'] != '') $address .= 'RT ' . $input['rt'] . ' ';
										if($input['rw'] != '') $address .= 'RW ' . $input['rw'] . ' ';
										if($input['kelurahan'] != '') $address .= $input['kelurahan'] . ' ';
										if($input['wilayah_id'] != '') 
										{
										$data = \Siakad\Wilayah::dataKecamatan($input['wilayah_id']) -> first();
										$address .= trim($data -> kec) . ' ' . trim($data -> kab) . ' ' . trim($data -> prov) . ' ';
										}
										if($input['kodePos'] != '') $address .= $input['kodePos'];
									*/
									$member = [
									'member_id' => $authinfo['username'],
									'member_name' => $data['nama'],
									'gender' => $gender,
									'birth_date' => $birth_date,
									'member_type_id' => 1,
									//'member_address' => $address,
									'member_phone' => $data['hp'],
									'member_since_date' => $today,
									'register_date' => $today,
									'input_date' => $today,
									'last_update' => $today,
									'expire_date' => date('Y-m-d', strtotime("+4 years")),
									'mpasswd' => md5($password)
									];
									\Siakad\SenayanMembership::create($member);
								}
								
								$success ++;
							}
							else
							{
								$failed ++;
								$fail[] = ['NIM' => $data['NIM'], 'nama' => $data['nama']];								
							}
						}
					}				
				}
				
				//delete file
				$storage -> delete($date . $filename);
				
				if($success == 0) return Redirect::route('mahasiswa.import') -> withErrors(['error' => 'Impor gagal. ' . $failed . ' data gagal diproses. Pastikan semua data sudah diisi dengan benar, dan data Mahasiswa belum terdaftar']);
				
				if($success > 0)
				{
					if($failed == 0) return Redirect::route('mahasiswa.import') -> with('success', 'Impor sukses. ' . $success . ' data berhasil dimasukkan. Login untuk SIAKAD dan SENAYAN berhasil dibuat dengan Username: [NIM Mahasiswa], Password: ' . $password);
					return Redirect::route('mahasiswa.import') -> with('warning_raw', '<strong>' . $success . '</strong> data berhasil dimasukkan. Login untuk SIAKAD dan SENAYAN berhasil dibuat dengan Username: [NIM Mahasiswa], Password: ' . $password . '<br/><strong>' . $failed . '</strong> data gagal diproses. ');
				}
			}
		}
		
		/* public function exportTranskripMerge()
			{
			$angkatan = \DB::select('
			select mahasiswa.angkatan, count(angkatan) as jumlah
			from mahasiswa
			group by angkatan
			order by angkatan desc
			');
			return view('dataexchange.transkrip_merge', compact('angkatan'));
		} */
		
		public function exportKurikulum()
		{
			$kurikulum = \DB::select("
			SELECT k.*, p.strata, p.nama AS prodi, p.singkatan AS singk, t.nama as tapel,
			SUM(CASE WHEN km.wajib='y' then m.sks_total else 0 end) as j_sks_wajib,
			SUM(CASE WHEN km.wajib='n' then m.sks_total else 0 end) as j_sks_pilihan 
			from `kurikulum` k
			left join kurikulum_matkul km on km.kurikulum_id = k.id
			left join matkul m on m.id = km.matkul_id
			left join prodi p on p.id = k.prodi_id
			left join tapel t on t.id = k.tapel_mulai
			group by k.id
			");
			return view('dataexchange.kurikulum', compact('kurikulum'));	
		}
		
		public function export($data)
		{
			$exportable = $this -> exportable;
			$exportableWarning = $this -> exportableWarning;
			
			if(!array_key_exists($data, $exportable)) abort(404);
			$prodi = \Siakad\Prodi::all();
			$format = ['xlsx'];			
			
			$hasTASelection = $this -> hasTASelection;
			$tapel = null;
			if(in_array($data, $hasTASelection)) 
			{	
				$raw = \Siakad\Tapel::orderBy('nama') -> lists('nama', 'id');
				$tapel = ['-- Pilih Tahun Akademik --'];
				foreach($raw as $k => $v) $tapel[$k] = $v;
			}
			
			if($data == 'transkrip_merge')
			{
				$angkatan = null;
				$raw = \DB::select('
				select mahasiswa.angkatan, count(angkatan) as jumlah
				from mahasiswa
				group by angkatan
				order by angkatan desc
				');
				$angkatan = ['Semua angkatan'];
				foreach($raw as $a) $angkatan[$a -> angkatan] = $a -> angkatan;
				
				foreach($prodi as $p) $tmp[$p -> singkatan] = $p -> nama . ' ('. $p -> singkatan . ')';
				$prodi = $tmp;
				
				return view('dataexchange.export_angkatan', compact('exportable', 'exportableWarning', 'data', 'format', 'prodi', 'angkatan'));		
			}
			
			return view('dataexchange.export', compact('exportable', 'exportableWarning', 'data', 'format', 'prodi', 'tapel', 'angkatan'));		
		}
		
		public function exportInto($data, $singk, $type, $var=null)
		{
			if(!array_key_exists($data, $this -> exportable)) abort(404);			
			$hasTASelection = $this -> hasTASelection;
			$rdata = null;
			$title = $this -> exportable[$data] . ' untuk import ke PDDIKTI FEEDER';
			$tpl = $data . '_tpl';
			
			$prodi = \Siakad\Prodi::whereSingkatan($singk) -> first();
			$ta = '';
			
			if(in_array($data, $hasTASelection)) 
			{
				$tapel2 = isset($tapel) && isset($tapel -> nama2) ? $tapel : ($var == null ? \Siakad\Tapel::whereAktif('y') -> first() : \Siakad\Tapel::whereId($var) -> first()); 
				$ta = $tapel2 -> nama2 . '-';
			}
			
			if($data == 'transkrip_merge')
			{
				$angkatan = null;
				if(intval($var) > 0)
				{
					$angkatan = $var;
					$ta = $var . '-';
				}
			}
			$filename = strtoupper(str_slug($this -> exportable[$data])) . '-' . $ta . $prodi -> singkatan  . '-' . date('Y-m-d H-i-s');
			
			//$cachekey = 'ekspor-'. $slug .'-' . $prodi -> singkatan;
			
			switch($data)
			{
				case 'kurikulum':
				//$rdata = Cache::get($cachekey, function() use ($slug, $cachekey, $var)  {
				//$kurikulum = \DB::select("
				$rdata = \DB::select("
				select m.*, km.semester from kurikulum k
				left join kurikulum_matkul km on k.id = km.kurikulum_id
				left join matkul m on m.id = km.matkul_id
				where k.id = :kid
				", ['kid' => $var]);					
				//Cache::put($cachekey, $kurikulum, 10);
				//return $kurikulum;
				//});
				break;
				
				case 'mahasiswa':
				//$rdata = Cache::get($cachekey, function() use ($slug, $cachekey)  {
				//$mahasiswa = \Siakad\Mahasiswa::where('prodi_id', $prodi -> id) -> get();
				$rdata = \Siakad\Mahasiswa::where('prodi_id', $prodi -> id) -> get();
				//Cache::put($cachekey, $mahasiswa, 10);
				//return $mahasiswa;
				//});
				break;
				
				case 'kelas':
				//$rdata = Cache::get($cachekey, function() use ($slug, $cachekey)  {
				//$kelas = \Siakad\MatkulTapel::exportDataKelas($prodi -> id) -> get();
				$rdata = \Siakad\MatkulTapel::exportDataKelas($prodi -> id, $var) -> get();
				//Cache::put($cachekey, $kelas, 10);
				//return $kelas;
				//});
				break;
				
				case 'krs':
				//$rdata = Cache::get($cachekey, function() use ($slug, $cachekey)  {
				//$krs = \Siakad\KrsDetail::exportData($prodi -> id) -> get();
				$rdata = \Siakad\KrsDetail::exportData($prodi -> id) -> get();
				//Cache::put($cachekey, $krs, 10);
				//return $krs;
				//});
				break;
				
				case 'dosen':
				//$rdata = Cache::get($cachekey, function() use ($slug, $cachekey)  {
				/* $dosen = \DB::select("SELECT SUM(jam) AS tatapmuka, mt.kelas2 AS kelas, matkul.kode, matkul.nama, dosen.nama AS nama_dosen, dosen.NIDN, t.nama2 AS semester FROM absensi_dosen ad
					JOIN dosen on dosen.id = ad.dosen_id
					JOIN matkul_tapel mt ON mt.id = ad.matkul_tapel_id
					JOIN matkul ON mt.matkul_id = matkul.id
					JOIN tapel t ON t.id = mt.tapel_id
					WHERE t.aktif='y' AND (STR_TO_DATE(ad.tanggal, '%d-%m-%Y') BETWEEN t.mulai AND t.selesai) AND prodi_id = ". $prodi -> id . "
				GROUP BY matkul.nama, nama_dosen"); */
				//$dosen = \DB::select("
				
				$tapel = $var === null ? "t.aktif='y'" : 't.id=' . $var;
				$rdata = \DB::select("
				SELECT mt.kelas2 AS kelas, matkul.kode, matkul.nama, dosen.nama AS nama_dosen, dosen.NIDN, t.nama2 AS tapel, km.semester 
				FROM matkul_tapel mt 
				JOIN dosen on dosen.id = mt.dosen_id
				JOIN kurikulum_matkul km ON km.id = mt.kurikulum_matkul_id
				JOIN matkul ON km.matkul_id = matkul.id
				JOIN tapel t ON t.id = mt.tapel_id
				WHERE " . $tapel . "
				AND mt.prodi_id = ". $prodi -> id . " AND dosen.id > 0
				");
				//Cache::put($cachekey, $dosen, 10);
				//return $dosen;
				//});
				break;
				
				case 'nilai':
				//$rdata = Cache::get($cachekey, function() use ($slug, $cachekey)  {
				//$nilai = \DB::select("
				$tapel = $var === null ? "t.aktif='y'" : 't.id=' . $var;
				$rdata = \DB::select("
				SELECT m.NIM, m.nama as nama_mahasiswa, matkul.kode, matkul.nama as nama_matkul, t.nama2 as tapel, mt.kelas2 as kelas, n.nilai, km.semester 
				FROM nilai n
				JOIN mahasiswa m on m.id = n.mahasiswa_id
				JOIN matkul_tapel mt on n.matkul_tapel_id = mt.id
				JOIN kurikulum_matkul km ON km.id = mt.kurikulum_matkul_id
				JOIN matkul ON km.matkul_id = matkul.id
				JOIN tapel t on t.id = mt.tapel_id
				WHERE n.jenis_nilai_id = 0 
				AND " . $tapel . " 
				AND mt.prodi_id = " . $prodi -> id);
				//Cache::put($cachekey, $nilai, 10);
				//return $nilai;
				//});
				break;
				
				case 'akm':
				// $rdata = Cache::get($cachekey, function() use ($slug, $cachekey)  {		
				$tapel = $var == null ? \Siakad\Tapel::whereAktif('y') -> first() : \Siakad\Tapel::whereId($var) -> first();
				$mahasiswa = \Siakad\Mahasiswa::where('statusMhs', '1') -> where('prodi_id', $prodi -> id) -> get();					
				foreach($mahasiswa as $m)
				{
					$data = \Siakad\Nilai::dataKHS($m -> NIM) -> get();
					$nilai = true;
					$cursks = $cursksn = $ipk = $sksk = $ip = $sksnk = $semester = 0;
					if(count($data) < 1) $nilai = false;
					else
					{
						foreach($data as $d) 
						{
							if($d -> aktif == 'y')
							{
								$cursks += $d -> sks;
								$cursksn += array_key_exists($d -> nilai, config('custom.konversi_nilai.base_4')) ? config('custom.konversi_nilai.base_4')[$d -> nilai] * $d -> sks : 0; 
							}
							$sksk += $d -> sks;
							$sksnk += array_key_exists($d -> nilai, config('custom.konversi_nilai.base_4')) ? config('custom.konversi_nilai.base_4')[$d -> nilai] * $d -> sks : 0; 						
						}
						$ip = $cursks < 1 ? 0: round($cursksn / $cursks, 2);
						
						$ipk = $sksk < 1 ? 0: round($sksnk / $sksk, 2);
					}
					$status = isset(config('custom.pilihan.statusMhs2')[$m -> statusMhs]) ? config('custom.pilihan.statusMhs2')[$m -> statusMhs] : 'K';
					$rdata[$m -> NIM] = ['NIM' => $m -> NIM, 'nama' => $m -> nama, 'semester' => $tapel -> nama2, 'sks' => $cursks, 'ip' => $ip, 'sksk' => $sksk, 'ipk' => $ipk, 'status' => $status];
				}
				//Cache::put($cachekey, $tmp, 30);
				//return $tmp;
				//});
				break;
				
				case 'kelulusan':
				//$rdata = Cache::get($cachekey, function() use ($slug, $cachekey)  {		
				$tapel = $var == null ? \Siakad\Tapel::whereAktif('y') -> first() : \Siakad\Tapel::whereId($var) -> first();
				$mahasiswa = \DB::select("
				SELECT m.*, s.judul, d.NIDN as pembimbing, w.*, 
				date_format(STR_TO_DATE(b.tglBimbingan, '%d-%m-%Y'), '%Y-%m-%d') as bimbingan
				FROM mahasiswa m
				LEFT JOIN skripsi s ON m.skripsi_id = s.id
				LEFT JOIN dosen_skripsi ds ON ds.skripsi_id = s.id
				LEFT JOIN dosen d ON ds.dosen_id = d.id
				LEFT JOIN bimbingan_skripsi b on b.skripsi_id = s.id
				LEFT JOIN wisuda w ON m.wisuda_id = w.id
				WHERE m.prodi_id = :id;
				", ['id' => $prodi -> id]);	
				
				foreach($mahasiswa as $m)
				{
					$data = \Siakad\Nilai::dataKHS($m -> NIM) -> get();
					$nilai = true;
					$cursks = $cursksn = $ipk = $sksk = $ip = $sksnk = $semester = 0;
					
					if(count($data) < 1) $nilai = false;
					else
					{
						foreach($data as $d) 
						{
							if($d -> aktif == 'y')
							{
								$cursks += $d -> sks;
								$cursksn += array_key_exists($d -> nilai, config('custom.konversi_nilai.base_4')) ? config('custom.konversi_nilai.base_4')[$d -> nilai] * $d -> sks : 0; 
							}
							$sksk += $d -> sks;
							$sksnk += array_key_exists($d -> nilai, config('custom.konversi_nilai.base_4')) ? config('custom.konversi_nilai.base_4')[$d -> nilai] * $d -> sks : 0; 						
						}
						$ip = $cursks < 1 ? 0: round($cursksn / $cursks, 2);
						
						$ipk = $sksk < 1 ? 0: round($sksnk / $sksk, 2);
					}
					
					if(isset($m -> bimbingan))
					{
						if(!isset($awal[$m -> NIM]))
						{
							$awal[$m -> NIM] = $m -> bimbingan;
						}
						else
						{
							if(strtotime($awal[$m -> NIM]) >strtotime( $m -> bimbingan))
							{
								$awal[$m -> NIM] = $m -> bimbingan;
							}
						}
						
						if(!isset($akhir[$m -> NIM]))
						{
							$akhir[$m -> NIM] = $m -> bimbingan;
						}
						else
						{
							if(strtotime($akhir[$m -> NIM]) < strtotime($m -> bimbingan))
							{
								$akhir[$m -> NIM] = $m -> bimbingan;
							}
						}
					}
					else
					{
						$akhir[$m -> NIM] = $awal[$m -> NIM] = '';
					}
					
					if(isset($m -> pembimbing))
					{
						if(!isset($pemb1[$m -> NIM])) 
						{
							$pemb1[$m -> NIM] = $m -> pembimbing;
							$pemb2[$m -> NIM] = '';
						}
						else $pemb2[$m -> NIM] = $m -> pembimbing;
					}
					else
					{
						$pemb1[$m -> NIM] = $pemb2[$m -> NIM] = '';
					}
					
					//$tmp[$m -> NIM] = [
					$rdata[$m -> NIM] = [
					'NIM' => $m -> NIM, 
					'nama' => $m -> nama, 
					'jenisKeluar' => $m -> statusMhs, 
					'tglKeluar' => $m -> tglKeluar, 
					'SKYudisium' => $m -> SKYudisium, 
					'tglSKYudisium' => isset($m -> tglSKYudisium) ? date('Y-m-d', strtotime($m -> tglSKYudisium))  : '', 
					'ipk' => $ipk, 
					'noIjazah' => $m -> noIjazah, 
					'judulSkripsi' => isset($m -> judul) ? $m -> judul : '', 
					'awalBimbingan' => $awal[$m -> NIM], 
					'akhirBimbingan' => $akhir[$m -> NIM], 
					'jalurSkripsi' => 1, 
					'pembimbing1' => $pemb1[$m -> NIM],
					'pembimbing2' => $pemb2[$m -> NIM]
					];
				}
				//Cache::put($cachekey, $tmp, 30);
				//return $tmp;
				//});
				break;
				
				case 'transkrip_merge':
				$tm = \Siakad\Nilai::TranskripMerge($prodi -> id, $angkatan) -> get();
				$mk = \Siakad\MatkulTapel::matkulProdi($prodi -> id)  -> get();
				$konversi = config('custom.konversi_nilai.base_4');
				$tmp = null;
				foreach($tm as $t)
				{
					$tmp[$t -> id]['nilai'][$t -> matkul] = $t -> nilai;
					$tmp[$t -> id]['nsks'][$t -> matkul] = $t -> nilai != '' ? $konversi[$t -> nilai] * $t -> sks : '';
					$tmp[$t -> id]['sks'][$t -> matkul] = $t -> sks;
					$tmp[$t -> id]['nama'] = $t -> nama;
					$tmp[$t -> id]['ttl'] = $t -> tmpLahir . ', ' . $t -> tglLahir;
					$tmp[$t -> id]['npm'] = $t -> NIM;
					$tmp[$t -> id]['nirm'] = $t -> NIRM;
					$tmp[$t -> id]['nirl1'] = $t -> NIRL1;
					$tmp[$t -> id]['nirl2'] = $t -> NIRL2;
					$tmp[$t -> id]['judul'] = $t -> judul;
				}
				$rdata = $tmp != null ? compact('tmp', 'mk') : null;
				// return view('dataexchange.' . $tpl) -> with(compact('rdata'));
				break;
			}
			
			if($rdata === null or $rdata === false) return view('dataexchange.empty');
			
			if($type == 'xlsx')
			{
				$this -> toXlsx($filename, $title, $tpl, $rdata);
			}	
		}
		
		private function toXlsx($filename, $title, $tpl, $rdata)
		{
			Excel::create($filename, function($excel) use($title, $tpl, $rdata) {
				$excel
				-> setTitle($title)
				-> setCreator('schz')
				-> setLastModifiedBy('Schz')
				-> setManager('Schz')
				-> setCompany('Schz. Co')
				-> setKeywords('Al-Hikam, STAIMA, STAIMA Al-Hikam Malang, '. $title)
				-> setDescription($title);
				$excel -> sheet('title', function($sheet) use($tpl, $rdata){
					$sheet
					-> setOrientation('landscape')
					-> setFontSize(12)
					-> loadView('dataexchange.' . $tpl) -> with(compact('rdata'));
				});
				
			}) -> download('xlsx');
			return;
		}
		
	private function numberToLetter($number, $base = 'base_4'){
	$number = intval($number);
	if($base === 'base_100'){
	switch($number){
	case ($number <= 100 and $number > 90):
	$ret = 'A+';break;
	
	case ($number <= 90 and $number > 85):
	$ret = 'A';break;
	
	case ($number <= 85 and $number > 80):
	$ret = 'A-';break;
	
	case ($number <= 80 and $number > 75):
	$ret = 'B+';break;
	
	case ($number <= 75 and $number > 70):
	$ret = 'B';break;
	
	case ($number <= 70 and $number > 65):
	$ret = 'B-';break;
	
	case ($number <= 65 and $number > 60):
	$ret = 'C+';break;
	
	case ($number <= 60 and $number > 55):
	$ret = 'C';break;
	
	case ($number <= 55 and $number > 50):
	$ret = 'C-';break;
	
	default:
	$ret = 'D';
	}
	}else{
	if($number <= 4 && $number > 3.75){
	$ret = 'A+';
	}elseif($number <= 3.75 && $number > 3.5){
	$ret = 'A';
	}elseif($number <= 3.5 && $number > 3.25){
	$ret = 'A-';
	}elseif($number <= 3.25 && $number > 3){
	$ret = 'B+';
	}elseif($number <= 3 && $number > 2.75){
	$ret = 'B';
	}elseif($number <= 2.75 && $number > 2.5){
	$ret = 'B-';
	}elseif($number <= 2.5 && $number > 2.25){
	$ret = 'C+';
	}elseif($number <= 2.25 && $number > 2){
	$ret = 'C';
	}elseif($number <= 2 && $number > 1.75){
	$ret = 'C-';
	}else{
	$ret = 'D';
	}
	}
	return $ret;
	}
	
	
	/* public function template()
	{
	$this -> data = [
	'NIM' => '' . $item['nim'],
	'prodi_id' => $prodi_id,
	'nama' => $item['nama'],
	'tmpLahir' => $item['tempat_lahir'],
	'tglLahir' => $item['tanggal_lahir'] == '-' ? NULL : $item['tanggal_lahir'],
	'jenisKelamin' => strtoupper($item['jenis_kelamin']),
	'NIK' => $item['nik'],
	'agama' => intval($item['agama']),
	'NISN' => $item['nisn'],
	'jalurMasuk' => intval($item['id_jalur_masuk']),
	'NPWP' => $item['npwp'],
	'wargaNegara' => $item['kewarganegaraan'],
	'jenisPendaftaran' => intval($item['jenis_pendaftaran']),
	'tglMasuk' => $item['tgl_masuk_kuliah'] == '-' ? NULL : $item['tgl_masuk_kuliah'],
	'tapelMasuk' => intval($item['mulai_semester']),
	'jalan' => $item['jalan'],
	'rt' => $item['rt'],
	'rw' => $item['rw'],
	'dusun' => isset($item['dusun_lingkungan']) ? $item['dusun_lingkungan'] : $item['nama_dusun'],
	'kelurahan' => isset($item['desa_kelurahan']) ? $item['desa_kelurahan'] : $item['kelurahan'],
	'id_wil' => $item['kecamatan'],
	'kodePos' => '' . $item['kode_pos'],
	'mukim' => intval($item['jenis_tinggal']),
	'transportasi' => intval($item['alat_transportasi']),
	'telp' => $item['telp_rumah'],
	'hp' => $item['no_hp'],
	'email' => $item['email'],
	'kps' => strtoupper($item['terima_kps']),
	'noKps' => $item['no_kps'],
	
	'NIKAyah' => $item['nik_ayah'],
	'namaAyah' => $item['nama_ayah'],
	'tglLahirAyah' => $item['tgl_lahir_ayah'],
	'pendidikanAyah' => intval($item['pendidikan_ayah']),
	'pekerjaanAyah' => intval($item['pekerjaan_ayah']),
	'penghasilanAyah' => intval($item['penghasilan_ayah']),
	
	'NIKIbu' => $item['nik_ibu'],
	'namaIbu' => '' . $item['nama_ibu'],
	'tglLahirIbu' => $item['tanggal_lahir_ibu'],
	'pendidikanIbu' => intval($item['pendidikan_ibu']),
	'pekerjaanIbu' => intval($item['pekerjaan_ibu']),
	'penghasilanIbu' => intval($item['penghasilan_ibu']),
	
	'namaWali' => $item['nama_wali'],
	'tglLahirWali' => $item['tanggal_lahir_wali'],
	'pendidikanWali' => intval($item['pendidikan_wali']),
	'pekerjaanWali' => intval($item['pekerjaan_wali']),
	'penghasilanWali' => intval($item['penghasilan_wali'])
	];
	
	Excel::create('mahasiswa-prodi-' . date('Y-m-d H-i-s'), function($excel) {
	$excel
	->setTitle('Data Mahasiswa')
	->setCreator('schz')
	->setLastModifiedBy('Schz')
	->setManager('Schz')
	->setCompany('Schz. Co')
	->setKeywords('Al-Hikam, STAIMA, STAIMA Al-Hikam Malang, Data Mahasiswa')
	->setDescription('Template impor Data Mahasiswa');
	$excel->sheet('title', function($sheet) {
	$sheet
	-> setOrientation('landscape')
	-> setFontSize(12)
	-> loadView('dataexchange.mahasiswa_tpl') -> with($this -> data);
	});
	
	})->download('xlsx');
	} */
	}
		
<?php
	
	namespace Siakad\Http\Controllers;
	
	use Excel;
	use Cache;
	use Redirect;
	
	use Illuminate\Support\Facades\Input;
	use Illuminate\Http\Request;
	
	use Siakad\Biaya;
	use Siakad\BiayaKuliah;
	use Siakad\Prodi;
	use Siakad\Kelas;
	
	use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	class BiayaController extends Controller
	{
		use \Siakad\NeracaTrait;
		use \Siakad\MahasiswaTrait;
		
		protected $rules = [
		'jumlah' => ['required', 'numeric', 'min:1']
		];
		
		//Mahasiswa
		public function tanggunganMahasiswa()
		{
			$data = \Auth::user() -> authable;	
			$jenisPembayaran = $data -> jenisPembayaran;
			$semesters = array_combine($r = range(1, $data -> semesterMhs), $r);
			$angkatan = $data -> angkatan;
			$jenis = BiayaKuliah::jenisPembayaran(['angkatan' => $angkatan, 'prodi_id' => $data -> prodi_id, 'program_id' => $data -> kelasMhs, 'jenisPembayaran' => $jenisPembayaran]) -> get();
			if($jenis -> count())
			{
				foreach($jenis as $j)
				{
					$jenis_list[$j -> id] = $j -> nama;	
					$query[] = 'SELECT '. $j -> id .' AS id, "' . $j -> nama . '" AS nama, ' . $j -> tanggungan . ' AS tanggungan, IFNULL(SUM(jumlah), 0) AS dibayar,  (' . $j -> tanggungan . ' - IFNULL(SUM(jumlah),0)) AS sisa FROM biaya WHERE jenis_biaya_id = '. $j -> id .' AND mahasiswa_id = ' . $data -> id;
				}
				$status = \DB::select(implode(' UNION ALL ', $query));
				
			}
			return view('biaya.mahasiswa.tanggungan', compact('status'));
		}
		public function riwayatMahasiswa()
		{
			$data = \Auth::user() -> authable;	
			$histories = Biaya::with('jenis') -> with('petugas') -> where('mahasiswa_id', $data -> id) -> orderBy('id', 'desc') -> paginate(50);
			return view('biaya.mahasiswa.riwayat', compact('histories'));
		}
		
		public function detail($tahun = null, $prodi_id = 1, $program_id = 1, $jenisPembayaran = 1)
		{
			$data['angkatan'] = $tahun;
			$data['prodi_id'] = $prodi_id;
			$data['program_id'] = $program_id;
			$data['jenisPembayaran'] = $jenisPembayaran;
			
			$angkatan = \DB::select('SELECT DISTINCT `angkatan` AS `tahun` FROM `mahasiswa` order by angkatan');
			foreach($angkatan as $a) $tmp[$a -> tahun] = $a -> tahun;
			$angkatan = $tmp;
			
			if($tahun == null) $data['angkatan'] = array_values($tmp)[0];
			
			$prodi = Prodi::orderBy('nama') -> lists('nama', 'id');
			$program = Kelas::orderBy('nama') -> lists('nama', 'id');
			
			$tprodi = Prodi::whereId($prodi_id) -> pluck('singkatan');
			$tprogram = Kelas::whereId($program_id) -> pluck('nama');
			$tjenisPembayaran = config('custom.pilihan.jenisPembayaran')[$jenisPembayaran];
			$title = $tjenisPembayaran . ' PRODI ' . $tprodi . ' ' . $tprogram . ' ' . $data['angkatan'];
			
			//heavy lift
			$mahasiswa = \Siakad\Mahasiswa::where('angkatan', $data['angkatan']) 
			-> where('prodi_id', $data['prodi_id']) 
			-> where('kelasMhs', $data['program_id'])
			-> where('jenisPembayaran', $jenisPembayaran)
			-> get();
			
			$jenis = BiayaKuliah::jenisPembayaran($data) -> get();
			
			/* 			$key = md5('rincian' . $data['angkatan'].$data['prodi_id'].$data['program_id'].$data['program_id']));
				if(Cache::has($key)
				{
				$rincian = Cache::get($key);	
				}
				else
			{ */
			$q = '';
			$rincian = [];
			if($jenis -> count())
			{
			if($mahasiswa -> count())
			{
				foreach($mahasiswa as $m)
				{
					$query = [];
					foreach($jenis as $j)
					{
						$jenis2[$j -> id] = ['nama' => $j -> nama, 'tanggungan' => $j -> tanggungan];
						$query[] = 'SELECT '. $j -> id .' AS id, IFNULL(SUM(jumlah), 0) AS dibayar FROM biaya WHERE jenis_biaya_id = '. $j -> id .' AND mahasiswa_id = ' . $m -> id;
					}
					$q .= implode(' UNION ALL ', $query) . ';' ;
					$rincian[$m -> id . '-' . $m -> nama] =  \DB::select(implode(' UNION ALL ', $query));
				}
			}
			else
			{
				foreach($jenis as $j)
				{
					$jenis2[$j -> id] = ['nama' => $j -> nama, 'tanggungan' => $j -> tanggungan];
				}
			}
			}
			
			
			/* 	Cache::put($key, $rincian, 60);
			} */
			
			return view('biaya.rincianbiaya', compact('angkatan', 'prodi', 'program', 'data', 'rincian', 'title', 'jenis2'));
		}
		
		public function detailSave($tahun = null, $prodi_id = 1, $program_id = 1, $jenisPembayaran = 1)
		{
			$format= Input::get('format');
			if($format != 'xlsx' and $format != 'pdf') return Redirect::back() -> withErrors(['error' => 'Format tidak ditemukan']);
			$data['angkatan'] = $tahun == null ? date('Y') : $tahun;
			$data['prodi_id'] = $prodi_id;
			$data['program_id'] = $program_id;
			$data['jenisPembayaran'] = $jenisPembayaran;
			
			$tprodi = Prodi::whereId($prodi_id) -> pluck('singkatan');
			$tprogram = Kelas::whereId($program_id) -> pluck('nama');
			$tjenisPembayaran = config('custom.pilihan.jenisPembayaran')[$jenisPembayaran];
			$title = $tjenisPembayaran . ' PRODI ' . $tprodi . ' ' . $tprogram . ' ' . $data['angkatan'];
			
			//heavy lift
			$mahasiswa = \Siakad\Mahasiswa::where('angkatan', $data['angkatan'])
			-> where('prodi_id', $data['prodi_id'])
			-> where('kelasMhs', $data['program_id'])
			-> where('jenisPembayaran', $jenisPembayaran)
			-> get();
			
			$jenis = BiayaKuliah::jenisPembayaran($data) -> get();
			
			if($jenis -> count() < 1)  return Redirect::back() -> withErrors(['error' => 'Data tidak ditemukan']);
			
			$q = '';
			$rincian = [];
			
			foreach($mahasiswa as $m)
			{
				$query = [];
				foreach($jenis as $j)
				{
					$jenis2[$j -> id] = ['nama' => $j -> nama, 'tanggungan' => $j -> tanggungan];
					$query[] = 'SELECT '. $j -> id .' AS id, IFNULL(SUM(jumlah), 0) AS dibayar FROM biaya WHERE jenis_biaya_id = '. $j -> id .' AND mahasiswa_id = ' . $m -> id;
				}
				$q .= implode(' UNION ALL ', $query) . ';' ;
				$rincian[$m -> id . '-' . $m -> nama] =  \DB::select(implode(' UNION ALL ', $query));
			}
			
			$this -> d = compact('data', 'rincian', 'title', 'jenis2');
			if($format == 'xlsx')
			{
				Excel::create('rekap-pmb-online-' . date('Y-m-d H-i-s'), function($excel) {
					$excel
					->setTitle('Rincian Biaya Pendidikan')
					->setCreator('schz')
					->setLastModifiedBy('Schz')
					->setManager('Schz')
					->setCompany('Schz. Co')
					->setKeywords('Al-Hikam, STAIMA, STAIMA Al-Hikam Malang, Rincian Biaya Pendidikan')
					->setDescription('Rincian Biaya Pendidikan format excel');
					$excel->sheet('Rincian Biaya Pendidikan', function($sheet) {
						$sheet
						-> setOrientation('landscape')
						-> setFontSize(12)
						-> loadView('biaya.rinciansave') -> with($this->d);
					});
					
				})->download('xlsx');
				return;
			}
			
			/* if($format == 'pdf')
				{
				return \PDF::loadView('biaya.rinciansave', $this->d) -> setOrientation('landscape') -> download('rekap-pmb-online-' . date('Y-m-d H-i-s') . '.pdf');
			}  */
		}
		public function detailCetak($tahun = null, $prodi_id = 1, $program_id = 1, $jenisPembayaran = 1)
		{
			$data['angkatan'] = $tahun == null ? date('Y') : $tahun;
			$data['prodi_id'] = $prodi_id;
			$data['program_id'] = $program_id;
			$data['jenisPembayaran'] = $jenisPembayaran;
			
			$tprodi = Prodi::whereId($prodi_id) -> pluck('singkatan');
			$tprogram = Kelas::whereId($program_id) -> pluck('nama');
			$tjenisPembayaran = config('custom.pilihan.jenisPembayaran')[$jenisPembayaran];
			$title = $tjenisPembayaran . ' PRODI ' . $tprodi . ' ' . $tprogram . ' ' . $data['angkatan'];
			
			//heavy lift
			$mahasiswa = \Siakad\Mahasiswa::where('angkatan', $data['angkatan']) 
			-> where('prodi_id', $data['prodi_id'])
			-> where('kelasMhs', $data['program_id'])
			-> where('jenisPembayaran', $jenisPembayaran)
			-> get();
			$jenis = BiayaKuliah::jenisPembayaran($data) -> get();
			
			$q = '';
			$rincian = [];
			
			foreach($mahasiswa as $m)
			{
				$query = [];
				foreach($jenis as $j)
				{
					$jenis2[$j -> id] = ['nama' => $j -> nama, 'tanggungan' => $j -> tanggungan];
					$query[] = 'SELECT '. $j -> id .' AS id, IFNULL(SUM(jumlah), 0) AS dibayar FROM biaya WHERE jenis_biaya_id = '. $j -> id .' AND mahasiswa_id = ' . $m -> id;
				}
				$q .= implode(' UNION ALL ', $query) . ';' ;
				$rincian[$m -> id . '-' . $m -> nama] =  \DB::select(implode(' UNION ALL ', $query));
			}
			return view('biaya.rincianprint', compact('data', 'rincian', 'title', 'jenis2'));
		}
		
		/* 		public function report($tahun = null, $prodi_id = 1, $program_id = 1, $jenisPembayaran = 1)
			{
			$data['angkatan'] = $tahun == null ? date('Y') : $tahun;
			$data['prodi_id'] = $prodi_id;
			$data['program_id'] = $program_id;
			$data['jenisPembayaran'] = $jenisPembayaran;
			$angkatan = \DB::select('SELECT DISTINCT LEFT(`nim`, 4) AS `tahun` FROM `mahasiswa`');
			foreach($angkatan as $a) $tmp[$a -> tahun] = $a -> tahun;
			$angkatan = $tmp;
			$prodi = Prodi::orderBy('nama') -> lists('nama', 'id');
			$program = Kelas::orderBy('nama') -> lists('nama', 'id');
			
			
			return view('biaya.laporan', compact('angkatan', 'prodi', 'program', 'data'));
		} */
		
		public function printReceipt($id)
		{
			$biaya = Biaya::with('mahasiswa') -> with('jenis') -> whereId($id) -> first();
			$credential = [];
			
			//update password & show it
			if($biaya -> jenis -> id == 2)
			{
				$user = \Siakad\User::where('authable_type', 'Siakad\Mahasiswa') -> where('authable_id', $biaya -> mahasiswa -> id);
				// dd($user -> first());
				if($user -> count())
				{
					$data = $user -> first();
					$credential['username'] = $data -> username;
					$credential['password'] = rand(00000000, 99999999);
					$password = bcrypt($credential['password']);
					$user -> update(['password' => $password]);
				}
			}
			
			return view('biaya.kwitansi', compact('biaya', 'credential'));
		}
		
		public function printStatus($nim)
		{
			$mahasiswa = \Siakad\Mahasiswa::where('NIM', $nim) -> first();
			$angkatan = $mahasiswa -> angkatan;
			$jenis = BiayaKuliah::jenisPembayaran(['angkatan' => $angkatan, 'prodi_id' => $mahasiswa -> prodi_id, 'program_id' => $mahasiswa -> kelasMhs, 'jenisPembayaran' => $mahasiswa -> jenisPembayaran]) -> get();
			
			foreach($jenis as $j)
			{
				$query[] = 'SELECT '. $j -> id .' AS id, "' . $j -> nama . '" AS nama, ' . $j -> tanggungan . ' AS tanggungan, IFNULL(SUM(jumlah), 0) AS dibayar,  (' . $j -> tanggungan . ' - IFNULL(SUM(jumlah),0)) AS sisa FROM biaya WHERE jenis_biaya_id = '. $j -> id .' AND mahasiswa_id = ' . $mahasiswa -> id;
			}
			
			$status = \DB::select(implode(' UNION ALL ', $query));
			
			/* 			if(count($status))
				{
			$query = []; */
			//SPP
			/* 				for($semester = 1; $semester <= $mahasiswa -> semesterMhs; $semester++)
				{
				$query[] = 'SELECT "' . $status[0] -> nama . ' Semester ' . $semester . '"  AS nama, ' . $status[0] -> tanggungan . ' AS tanggungan, IFNULL(SUM(jumlah), 0) AS dibayar,  (' . $status[0] -> tanggungan . ' - IFNULL(SUM(jumlah),0)) AS sisa FROM biaya WHERE jenis_biaya_id = 1 AND mahasiswa_id = ' . $mahasiswa -> id . ' AND semester = ' . $semester;
				}
			$spp = \DB::select(implode(' UNION ALL ', $query)); */
			//HER
			/* 				for($semester = 1; $semester <= $mahasiswa -> semesterMhs; $semester++)
				{
				$query[] = 'SELECT "' . $status[1] -> nama . ' Semester ' . $semester . '"  AS nama, ' . $status[1] -> tanggungan . ' AS tanggungan, IFNULL(SUM(jumlah), 0) AS dibayar,  (' . $status[1] -> tanggungan . ' - IFNULL(SUM(jumlah),0)) AS sisa FROM biaya WHERE jenis_biaya_id = 2 AND mahasiswa_id = ' . $mahasiswa -> id . ' AND semester = ' . $semester;
				}
				$her = \DB::select(implode(' UNION ALL ', $query));
			} */
			// return view('biaya.status', compact('status', 'her', 'mahasiswa'));
			return view('biaya.status', compact('status', 'mahasiswa'));
		}
		
		public function formSubmit(Request $request)
		{
			$this -> validate($request, $this -> rules);
			$input = Input::except('_token');
			$mahasiswa = \Siakad\Mahasiswa::where('NIM', $input['nim']) -> first();
			$angkatan = $mahasiswa -> angkatan;
			
			//tanggungan
			$tanggungan = BiayaKuliah::where('angkatan', $angkatan) 
			-> where('jenis_biaya_id', $input['jenis_biaya_id'])
			-> where('prodi_id', $input['prodi_id'])
			-> where('kelas_id', $input['program_id'])
			-> where('jenisPembayaran', $input['jenisPembayaran'])
			-> first();
			// dd($tanggungan);
			//tanggungan tidak ditemukan
			if(!$tanggungan) return Redirect::back() -> withErrors(['error' => 'Biaya kuliah belum diatur']);
			
			//sudah bayar ?
			$bayar = Biaya::where('jenis_biaya_id', $input['jenis_biaya_id']) 
			-> where('mahasiswa_id', $input['mahasiswa_id']);
			
			//SPP
			/* if($input['jenis_biaya_id'] == 1) 
				{
				$bayar = $bayar -> where('semester', $input['semester']);
			} */
			
			//HER
			// Pembayaran dianggap lunas jika data ditemukan
			if($input['jenis_biaya_id'] == 2) 
			{
				$bayar = $bayar -> where('semester', $input['semester']) -> select(\DB::raw('SUM(jumlah) as total')) -> first();
				if(intval($bayar -> total) > 0) return Redirect::back() -> with('message', 'Tanggungan sudah LUNAS');
			} 
			else
			{
				$bayar = $bayar -> select(\DB::raw('SUM(jumlah) as total')) -> first();
			}
			
			//sudah lunas?
			$tanggungan = intval($tanggungan -> jumlah);
			$total_dibayar = intval($bayar -> total);
			$sisa = $tanggungan - $total_dibayar;
			if($total_dibayar >= $tanggungan) return Redirect::back() -> with('message', 'Tanggungan sudah LUNAS');
			
			//kelebihan
			$msg2 = '';
			if($input['jumlah'] > $sisa) 
			{
				$kelebihan = $total_dibayar <= 0 ? $input['jumlah'] - $tanggungan : $input['jumlah'] - $sisa;
				$input['jumlah'] = $sisa;
				$msg2 = ' Terdapat kelebihan pembayaran Rp ' . number_format($kelebihan, 0, ',', '.');
			}
			
			$data = [
			'jenis_biaya_id' => $input['jenis_biaya_id'],
			'mahasiswa_id' => $input['mahasiswa_id'],
			'jumlah' => $input['jumlah'],
			'user_id' => \Auth::user() -> id
			];
			// if($input['jenis_biaya_id'] == 1) $data['semester'] = $input['semester'];
			if($input['jenis_biaya_id'] == 2) $data['semester'] = $input['semester'];
			$proses = Biaya::create($data);
			
			//Neraca
			$this -> storeIntoNeraca(['transable_id' => $proses -> id, 'transable_type' => 'Siakad\Biaya', 'jenis' => 'masuk', 'jumlah' => $input['jumlah']]);
			
			return redirect('/biaya/form?nim=' . $input['nim'] . '&update=true') -> with('success', 'Pembayaran berhasil.' . $msg2);
		}
		
		private function statusPembayaran()
		{
			
		}
		
		public function form()
		{
			$nim = null !== Input::get('nim') ? Input::get('nim') : null;
			$mahasiswa = $tmp = $spp = $status = $biaya = $jenis_list = null;
			$jenis = [];
			if($nim !== null)
			{
				//get from cache
				if(Input::get('update') != 'true')
				{
					$page_view = Cache::get(md5('form_pembayaran_' . $nim));
					if($page_view != null)
					{
						return $page_view;
					}	
				}	
				
				$mahasiswa = \Siakad\Mahasiswa::with('prodi') -> with('kelas') -> where('NIM', $nim) -> first();
				
				if($mahasiswa) 
				{
					$semesters = array_combine($r = range(1, $mahasiswa -> semesterMhs), $r);
					$angkatan = $mahasiswa -> angkatan;
					$jenis = BiayaKuliah::jenisPembayaran(['angkatan' => $angkatan, 'prodi_id' => $mahasiswa -> prodi_id, 'program_id' => $mahasiswa -> kelasMhs, 'jenisPembayaran' => $mahasiswa -> jenisPembayaran]) -> get();
					if($jenis -> count())
					{
						foreach($jenis as $j)
						{
							$jenis_list[$j -> id] = $j -> nama;	
							$query[] = 'SELECT '. $j -> id .' AS id, "' . $j -> nama . '" AS nama, ' . $j -> tanggungan . ' AS tanggungan, IFNULL(SUM(jumlah), 0) AS dibayar,  (' . $j -> tanggungan . ' - IFNULL(SUM(jumlah),0)) AS sisa FROM biaya WHERE jenis_biaya_id = '. $j -> id .' AND mahasiswa_id = ' . $mahasiswa -> id;
							$biaya[$j -> id] = $j -> tanggungan; //seharuse sisa pembayaran
						}
						$status = \DB::select(implode(' UNION ALL ', $query));
						if(count($status))
						{
							$query = [];
							//SPP
							/* for($semester = 1; $semester <= $mahasiswa -> semesterMhs; $semester++)
								{
								$query[] = 'SELECT "' . $status[0] -> nama . ' Semester ' . $semester . '"  AS nama, ' . $status[0] -> tanggungan . ' AS tanggungan, IFNULL(SUM(jumlah), 0) AS dibayar,  (' . $status[0] -> tanggungan . ' - IFNULL(SUM(jumlah),0)) AS sisa FROM biaya WHERE jenis_biaya_id = 1 AND mahasiswa_id = ' . $mahasiswa -> id . ' AND semester = ' . $semester;
								}
							$spp = \DB::select(implode(' UNION ALL ', $query)); */
							//HER
							/* 		for($semester = 1; $semester <= $mahasiswa -> semesterMhs; $semester++)
								{
								$query[] = 'SELECT "' . $status[1] -> nama . ' Semester ' . $semester . '"  AS nama, ' . $status[1] -> tanggungan . ' AS tanggungan, IFNULL(SUM(jumlah), 0) AS dibayar,  (' . $status[1] -> tanggungan . ' - IFNULL(SUM(jumlah),0)) AS sisa FROM biaya WHERE jenis_biaya_id = 2 AND mahasiswa_id = ' . $mahasiswa -> id . ' AND semester = ' . $semester;
								}
							$her = \DB::select(implode(' UNION ALL ', $query)); */
							
							//history
							$histories = Biaya::with('jenis') -> with('petugas') -> where('mahasiswa_id', $mahasiswa -> id) -> orderBy('id', 'desc') -> paginate(10);
						}
					}
				}
			}
			// return view('biaya.form', compact('mahasiswa', 'jenis', 'status', 'spp', 'semesters', 'nim', 'histories'));
			
			//cache
			// $page_view = view('biaya.form', compact('mahasiswa', 'jenis_list', 'status', 'her', 'semesters', 'nim', 'histories', 'biaya')) -> render();
			$page_view = view('biaya.form', compact('mahasiswa', 'jenis_list', 'status', 'semesters', 'nim', 'histories', 'biaya', 'jenisPembayaran')) -> render();
			Cache::put(md5('form_pembayaran_' . $nim), $page_view, 60);
			return $page_view;
		}
		
		public function destroy($nim, $id)
		{
			Biaya::find($id) -> delete();
			$this -> deleteFromNeraca(['transable_id' => $id, 'transable_type' => 'Siakad\\Biaya']);
			return redirect('/biaya/form?nim=' . $nim . '&update=true') -> with('success', 'Pembayaran berhasil dihapus.');
		}
	}

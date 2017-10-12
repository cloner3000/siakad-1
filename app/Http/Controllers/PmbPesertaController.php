<?php namespace Siakad\Http\Controllers;
	
	use Excel;
	use Input;
	use Redirect;
	
	use Siakad\PmbPeserta;
	use Siakad\Pmb;
	
	use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	use Illuminate\Http\Request;
	
	class PmbPesertaController extends Controller {
		protected $rules = [
		'nama' => 'required',
		'telpMhs' => 'required',
		'noKtp' => 'required',
		'slip' => 'required',
		];
		
		public function dialog($type)
		{
			$types = ['kartu' => 'Kartu Ujian', 'formulir' => 'Formulir Pendaftaran'];
			if(!isset($types[$type])) return view('pmb.peserta.error', 
			[
			'error' => 'not_found'
			]);
			
			return view('pmb.peserta.dialog', compact('type', 'types'));
		}
		
		public function destroy($id, $kode)
		{
			PmbPeserta::whereKode($kode) -> delete();
			return Redirect::route('pmb.peserta.index', $id) -> with('success', 'Pendaftar berhasil dihapus.');
		}
		
		public function printing($type, $kode)
		{
			$data = PmbPeserta::whereKode($kode) -> first();	
			if(!$data) return view('pmb.peserta.error', 
			[
			'error' => 'data_not_found'
			]);
			$pmb = Pmb::find($data -> pmb_id);
			$prodi = \Siakad\Prodi::orderBy('nama') -> get();
			foreach($prodi as $p) $tmp[$p -> id] = $p;
			$prodi = $tmp;
			
			if($type === 'formulir') return view('pmb.peserta.formulir', compact('data', 'pmb', 'prodi'));
			if($type === 'kartu') return view('pmb.peserta.kartu', compact('data', 'pmb', 'prodi'));
			
			return view('pmb.peserta.error', 
			[
			'error' => 'not_found'
			]);
			
		}
		
		public function index($id)
		{
			$pmb = Pmb::find($id);
			$peserta = $pmb -> peserta;
			return view('pmb.peserta.index', compact('pmb', 'peserta'));
		}
		
		public function exportTo($format='excel')
		{
			$column = array_except(\DB::getSchemaBuilder() -> getColumnListing('pmb'), [0,39,40, 41, 43]);
			$data = PmbPeserta::All();
			
			$this->d = compact('data', 'column');
			if($format == 'excel')
			{
				Excel::create('rekap-pmb-online-' . date('Y-m-d H-i-s'), function($excel) {
					$excel
					->setTitle('Rekap PMB Online')
					->setCreator('http://schz.pw')
					->setLastModifiedBy('Schz')
					->setManager('Schz')
					->setCompany('Schz. Co')
					->setKeywords('Schz, Al-Hikam, STAIMA, STAIMA Al-Hikam Malang,Rekap PMB Online')
					->setDescription('Rekap Pendaftaran Mahasiswa Baru Online format excel');
					$excel->sheet('Rekap PMB Online', function($sheet) {
						$sheet
						->setOrientation('landscape')
						->setFontSize(12)
						->loadView('pmb.excel')->with($this->d);
					});
					
				})->download('xlsx');
				return;
			}
			
			/* if($format == 'pdf')
				{
				return \PDF::loadView('pmb.excel', $this->d) -> setOrientation('landscape') -> download('rekap-pmb-online-' . date('Y-m-d H-i-s') . '.pdf');
			} */
		}
		
		public function graph()
		{
			
			if(null !== Input::get('p')){
				$tahun = Input::get('p');
			}
			else
			{
				$periode_aktif = \Siakad\Config::whereModule('pmb') -> whereAttribute('tahun') -> first();
				$tahun = $periode_aktif -> value;
			}
			
			$prodi = PmbPeserta::select(\DB::raw('jurusan, count(jurusan) as jumlah')) -> wherePeriode($tahun) -> groupBy('jurusan') -> get();
			$jk = PmbPeserta::select(\DB::raw('jenisKelamin, count(jenisKelamin) as jumlah')) -> wherePeriode($tahun) -> groupBy('jenisKelamin') -> orderBy('jenisKelamin', 'DESC') -> get();
			$pend = PmbPeserta::select(\DB::raw('sekolahAsal, count(sekolahAsal) as jumlah')) -> wherePeriode($tahun) -> groupBy('sekolahAsal') -> get();
			$lulus = PmbPeserta::select(\DB::raw('thLulus AS tahunLulus, count(thLulus) as jumlah')) -> wherePeriode($tahun) -> groupBy('tahunLulus') -> get();
			
			$periode = PmbPeserta::distinct('periode') -> get(['periode']);
			
			return view('pmb.peserta.graph', compact('prodi', 'jk', 'pend', 'lulus', 'tahun', 'periode'));
		}
		
		private function check($data = null, $ip, $key = null)
		{
			$pmb = ($data == null) ? Pmb::whereBuka('y') -> first() : $data;
			
			if(!$pmb or $pmb == null) return view('pmb.peserta.error', ['error' => null]) -> render();
			
			//check IP
			$db = PmbPeserta::where('pmb_id', $pmb -> id) -> where('ipAddr', $ip) -> orderBy('created_at', 'desc') -> first();
			if($db !== null and ($key == null or $key !== csrf_token())) 
			return view('pmb.peserta.error', 
			[
			'message' => 'Alamat IP anda sudah terdaftar di data kami atas nama: <strong>' . $db -> nama . '</strong>. ',
			'error' => 'ip'
			]) 
			-> render();
			
			$today = strtotime(date('Y-m-d'));
			if(strtotime($pmb -> mulai . ' 00:00:00') > $today or strtotime($pmb -> selesai . ' 00:00:00') < $today)  return view('pmb.peserta.error', 
			['error' => null, 'message' => 'Pendaftaran Mahasiswa Baru dibuka mulai tanggal ' . formatTanggal($pmb -> mulai) . ' - ' . formatTanggal($pmb -> selesai)]) -> render();
			/* $pendaftar = $pmb -> peserta -> count();
				if(($pendaftar + 1) > $pmb -> kuota) return view('pmb.peserta.error', 
			['message' => 'Kuota Pendaftaran Mahasiswa Baru pada Gelombang ini sudah terpenuhi.']) -> render(); */
			
			return null;
		}
		
		public function create(Request $request)
		{		
			$pmb = Pmb::whereBuka('y') -> first();
			$key = Input::get('key');
			$check = $this -> check($pmb, $request -> ip(), $key);
			if($check !== null) return $check;
			
			$tujuan = explode(',', $pmb -> tujuan) == null ? [] : explode(',', $pmb -> tujuan);
			foreach(\Siakad\Prodi::orderBy('nama') -> get() as $p) $prodi[$p -> id] = $p -> nama . ' (' . $p -> singkatan . ')';
			
			$today = strtotime(date('Y-m-d'));
			$mulai = strtotime($pmb -> mulai);
			$selesai = strtotime($pmb -> selesai);
			
			return view('pmb.peserta.create', compact('pmb', 'tujuan', 'prodi'));
		}
		
		public function store(Request $request)
		{
			$check = $this -> check(null, $request -> ip(), Input::get('key'));
			if($check !== null) return $check;
			// if($request -> ip() != '127.0.0.1') $this -> rules['g-recaptcha-response'] = ['required', 'captcha'];
			$this -> validate($request, $this -> rules);
			$input = array_except(Input::all(), ['_token','key']);
			
			/* $pmb = Pmb::whereBuka('y') -> first(); */
			
			/* $exists = PmbPeserta::where('nama', $input['nama'])
				-> where('pmb_id', $pmb -> id)
				-> where('telpMhs', $input['telpMhs'])
				-> where('noKtp', $input['noKtp'])
				-> first();
				
			if(isset($exists)) return redirect('/pmb/formulir/exist/' . $exists -> noPendaftaran);	 */	
			
			if(isset($input['p-lain-ayah']) and $input['p-lain-ayah'] != '') 
			{
				$input['pekerjaanAyah'] = $input['p-lain-ayah'];
				unset($input['p-lain-ayah']);				
			}
			if(isset($input['p-lain-ibu']) and $input['p-lain-ibu'] != '') 
			{
				$input['pekerjaanIbu'] = $input['p-lain-ibu'];
				unset($input['p-lain-ibu']);				
			}
			
			$maxid = \DB::select('select MAX(id) as maxid from `pmb_peserta`  WHERE `pmb_id` = ' . $input['pmb_id']);
			if(intval($maxid[0]->maxid) < 1) $maxid = 1; else $maxid = $maxid[0]->maxid + 1;
			$noPendaftaran = str_pad($maxid, 4, "0", STR_PAD_LEFT);
			$input['kode'] = str_random(6);
			$input['noPendaftaran'] = $noPendaftaran;
			$input['ipAddr'] = $request -> ip();
			$input['UA'] = $request -> header('user-agent');
			
			PmbPeserta::create($input);
			
			//send email
			$this -> dispatch(new SendPmbNotificationEmail($noPendaftaran));
			// $this -> sendEmail($noPendaftaran);
			
			return Redirect::route('pmb.peserta.stored', $input['kode']) -> with('message', 'Proses pendaftaran berhasil.');
		}
		
		public function stored($kode)
		{
			$data = PmbPeserta::whereKode($kode) -> first();	
			if(!$data) abort(404);
			$pmb = Pmb::find($data -> psb_id);
			return view('pmb.peserta.stored', compact('data', 'psb'));
		}
		
		/* public function sendEmail($no)
		{
			$data = PmbPeserta::where('noPendaftaran', $no) -> first();
			$to = explode(',', config('custom.profil.email'));
			
			// $ch = curl_init(env('MAIL_HOST') . ':' . env('MAIL_PORT'));
			$ch = curl_init(env('MAIL_HOST'));
			curl_setopt($ch, CURLOPT_NOBODY, true);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_exec($ch);
			$retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			curl_close($ch);
			
			if (200==$retcode) {
				// All's well
				foreach($to as $email)
				{
					\Mail::send('pmb.peserta.notifpendaftaran', ['data' => $data], function($message) use ($email)
					{
						$message->from(html_entity_decode('pmb@staima-alhikam.ac.id'), config('custom.abbr') ." " . htmlspecialchars_decode(config('custom.name'), ENT_QUOTES));
						$message->subject('Pendaftaran Mahasiswa Baru Sukses!');
						$message->to(trim($email));
					});
				}
				// return 'OK';
				} else {
				// not so much
				// return 'Error';
			}
		} */
		
		public function show($no_pendaftaran)
		{
			$configs = Config::whereModule('pmb') -> get();
		foreach($configs as $conf)
		{
		if($conf -> attribute == 'tahun') $tmp['tahun'] = $conf -> value;
		if($conf -> attribute == 'status') $tmp['status'] = $conf -> value;
		if($conf -> attribute == 'mulai') $tmp['mulai'] = $conf -> value;
		if($conf -> attribute == 'selesai') $tmp['selesai'] = $conf -> value;
		if($conf -> attribute == 'kuota-1') $tmp['kuota-1'] = $conf -> value;
		if($conf -> attribute == 'kuota-2') $tmp['kuota-2'] = $conf -> value;
		if($conf -> attribute == 'kuota-3') $tmp['kuota-3'] = $conf -> value;
		if($conf -> attribute == 'syarat') $tmp['syarat'] = $conf -> value;
		}
		$data = PmbPeserta::where('noPendaftaran', $no_pendaftaran) -> first();
		if(!$data) abort(404);
		return view('pmb.peserta.show', compact('data', 'tmp'));
		}	
		
		/* public function edit($no_pendaftaran)
		{
		if(Input::get('key') !== getPMBKey($no_pendaftaran)) return Redirect::route('pmb.konfirmasi', ['edit', $no_pendaftaran]);
		$mahasiswa = PmbPeserta::where('noPendaftaran', $no_pendaftaran)->first();
		
		$configs = Config::whereModule('pmb') -> get();
		foreach($configs as $conf)
		{
		if($conf -> attribute == 'tahun') $tmp['tahun'] = $conf -> value;
		if($conf -> attribute == 'status') $tmp['status'] = $conf -> value;
		if($conf -> attribute == 'mulai') $tmp['mulai'] = $conf -> value;
		if($conf -> attribute == 'selesai') $tmp['selesai'] = $conf -> value;
		if($conf -> attribute == 'kuota-1') $tmp['kuota-1'] = $conf -> value;
		if($conf -> attribute == 'kuota-2') $tmp['kuota-2'] = $conf -> value;
		if($conf -> attribute == 'kuota-3') $tmp['kuota-3'] = $conf -> value;
		if($conf -> attribute == 'syarat') $tmp['syarat'] = $conf -> value;
		}
		$tmp2 = \DB::select('select jurusan, count(jurusan) as pendaftar from pmb where periode = "'. $tmp['tahun'] .'" group by jurusan order by jurusan');
		
		foreach(config('custom.jurusan') as $k => $v) 
		{
		if(isset($tmp2[$k-1])) $pendaftar[$k] = $tmp2[$k-1] -> pendaftar;
		else $pendaftar[$k] = 0;
		}
		
		return view('pmb.peserta.edit', compact('mahasiswa', 'pendaftar', 'tmp'));		
		}	
		
		public function update(Request $request, $no_pendaftaran)
		{
		if($request -> ip() != '127.0.0.1') $this -> rules['g-recaptcha-response'] = ['required', 'captcha'];
		$this -> validate($request, $this -> rules);
		
		$input = array_except(Input::all(), ['_token', 'g-recaptcha-response', '_method']);
		if(isset($input['p-lain-ayah']) and $input['p-lain-ayah'] != '') 
		{
		$input['pekerjaanAyah'] = $input['p-lain-ayah'];			
		}
		if(isset($input['p-lain-ibu']) and $input['p-lain-ibu'] != '') 
		{
		$input['pekerjaanIbu'] = $input['p-lain-ibu'];		
		}
		unset($input['p-lain-ibu']);		
		unset($input['p-lain-ayah']);	
		
		$pmb = PmbPeserta::where('noPendaftaran', $no_pendaftaran);
		$input['ipAddr'] = $request -> ip();
		$input['UA'] = $request -> header('user-agent');
		$pmb -> update($input);
		
		$key = getPMBKey($no_pendaftaran);
		return view('pmb.peserta.updated', compact('no_pendaftaran', 'key'));
		}	
		*/
		}
				
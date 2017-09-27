<?php namespace Siakad\Http\Controllers;
	
	use Cache;
	class HomeController extends Controller {
		
		/*
			|--------------------------------------------------------------------------
			| Home Controller
			|--------------------------------------------------------------------------
			|
			| This controller renders your application's "dashboard" for users that
			| are authenticated. Of course, you are free to change or remove the
			| controller as you wish. It is just here to get your app started!
			|
		*/
		
		/**
			* Create a new controller instance.
			*
			* @return void
		*/
		public function __construct()
		{
			$this->middleware('auth');
		}
		
		/**
			* Show the application dashboard to the user.
			*
			* @return Response
		*/
		public function index()
		{
			$role = \Auth::user() -> role_id;
			if($role == 1 or $role == 2) 
			{
				$informasi = \Siakad\Informasi::orderBy('id', 'desc') -> take(5) -> get();
				$files = false;
			}
			else
			{
				$informasi = \Siakad\Informasi::where('akses', 'like', '%"' . \Auth::user() -> role_id . '"%') ->  orderBy('id', 'desc') -> take(5) ->get();
				$files = \Siakad\FileEntry::orderBy('created_at', 'desc') -> where('akses', 'LIKE', '%"'. $role .'"%') -> get();	
			}
			
			$counter['mahasiswa'] = Cache::get('counter_mahasiswa', function() {
				$c = \Siakad\Mahasiswa::count();
				Cache::put('counter_mahasiswa', $c, 60);
				return $c;
			});
			$counter['matkul'] = Cache::get('counter_matkul', function() {
				$c = \Siakad\Matkul::count();
				Cache::put('counter_matkul', $c, 60);
				return $c;
			});
			$counter['kelaskuliah'] = Cache::get('counter_kelaskuliah', function() {
				$c = \Siakad\MatkulTapel::count();
				Cache::put('counter_kelaskuliah', $c, 60);
				return $c;
			});
			$counter['dosen'] = Cache::get('counter_dosen', function() {
				$c = \Siakad\Dosen::count();
				Cache::put('counter_dosen', $c, 60);
				return $c;
			});			
			
			//Jumlah per JK per Angkatan
			$jk_angkatan = Cache::get('g_jk_angkatan', function() {
				$tmp = \DB::select("
				SELECT 
				SUM(IF(jenisKelamin = 'L', 1, 0)) AS jlk, 
				SUM(IF(jenisKelamin = 'P', 1, 0)) AS jpr, 
				angkatan 
				FROM mahasiswa 
				GROUP BY angkatan;
				");
				foreach($tmp as $t) 
				{
					$tmp2[$t -> angkatan]['L'] = $t -> jlk;
					$tmp2[$t -> angkatan]['P'] = $t -> jpr;
				}
				Cache::put('g_jk_angkatan', $tmp2, 60);
				return $tmp2;
			});
			
			//Jumlah Mahasiswa per PRODI
			$mhs_prodi = Cache::get('g_mhs_prodi', function() {
				$tmp = \DB::select("
				select count(*) as jumlah, prodi.nama as prodi from mahasiswa left join prodi on prodi.id = mahasiswa.prodi_id group by prodi_id
				");
				foreach($tmp as $t) 
				{
					$tmp2[$t -> prodi] = $t -> jumlah;
				}
				Cache::put('g_mhs_prodi', $tmp2, 60);
				return $tmp2;
			});
			
			//JK Dosen
			$jk_dosen = Cache::get('g_jk_dosen', function() {
				$tmp = \DB::select("
				select count(*) as jumlah, jenisKelamin from dosen group by jenisKelamin order by jenisKelamin
				");
				foreach($tmp as $t) 
				{
					$tmp2[$t -> jenisKelamin] = $t -> jumlah;
				}
				Cache::put('g_jk_dosen', $tmp2, 60);
				return $tmp2;
			});
			
			//Status Dosen
			$st_dosen = Cache::get('g_st_dosen', function() {
				$tmp = \DB::select("
				select sum(if(statusDosen = 1 and NIDN = '', 1, 0)) as dttn, sum(if(statusDosen = 1 and NIDN != '', 1, 0)) as dtn, sum(if(statusDosen = 2, 1, 0)) as dtt from dosen order by statusDosen
				");
				$tmp2[] = $tmp[0] -> dttn;
				$tmp2[] = $tmp[0] -> dtn;
				$tmp2[] = $tmp[0] -> dtt;
				
				Cache::put('g_st_dosen', $tmp2, 60);
				return $tmp2;
			});
			
			//Penugasan Dosen
			$png_dosen = Cache::get('g_png_dosen', function() {
				$tmp = \DB::select("
				select count(*) as jumlah, prodi.singkatan from dosen_penugasan left join prodi on prodi.id = dosen_penugasan.prodi_id group by prodi_id
				");
				foreach($tmp as $t) 
				{
					$tmp2[$t -> singkatan] = $t -> jumlah;
				}
				
				Cache::put('g_png_dosen', $tmp2, 60);
				return $tmp2;
			});
			
			$prov = Cache::get('g_prov', function() {
				// $tmp = \DB::select('SELECT p.id, p.nama AS prov, COUNT(NIM) AS jumlah FROM mahasiswa INNER JOIN wilayah_provinsi p ON p.id = mahasiswa.propinsi GROUP BY propinsi');
				$tmp = \DB::select('
				select prov.nm_wil AS prov, count(NIM) AS jumlah
				from mahasiswa m
				left join wilayah on wilayah.id_wil = m.id_wil
				inner join wilayah AS kab on kab.id_wil=wilayah.id_induk_wilayah
				inner join wilayah AS prov on prov.id_wil=kab.id_induk_wilayah
				group by prov.nm_wil;
				');
				foreach($tmp as $t) $tmp2[$t -> prov] = $t -> jumlah;
				Cache::put('g_prov', $tmp2, 60);
				return $tmp2;
			});
			
			$pk_ortu = Cache::get('g_pk_ortu', function() {
				// $s = \DB::select('SELECT pekerjaanOrtu, COUNT(NIM) AS jumlah FROM mahasiswa GROUP BY pekerjaanOrtu');
				$s = \DB::select('SELECT pekerjaanAyah, COUNT(NIM) AS jumlah FROM mahasiswa GROUP BY pekerjaanAyah');
				foreach($s as $st) $pk_ortu[$st -> pekerjaanAyah] = $st -> jumlah;
				foreach(config('custom.pilihan.pekerjaanOrtu') as $k => $v) 
				{
					$tmp[$k] = isset($pk_ortu[$k]) ? $pk_ortu[$k] : 0;
				}
				ksort($tmp);
				Cache::put('g_pk_ortu', $tmp, 60);
				return $tmp;
			});
			
			$status = Cache::get('g_status', function() {
				$s = \DB::select('SELECT statusMhs, COUNT(NIM) AS jumlah FROM mahasiswa GROUP BY statusMhs');
				foreach($s as $st) $status[$st -> statusMhs] = $st -> jumlah;
				foreach(config('custom.pilihan.statusMhs') as $k => $v) 
				{
					$tmp[$k] = isset($status[$k]) ? $status[$k] : 0;
				}
				ksort($tmp);
				Cache::put('g_status', $tmp, 60);
				return $tmp;
			});
			
			if(Cache::has('g_per_angkatan'))
			{
				$per_angkatan = Cache::get('g_per_angkatan');
				$lulusan = Cache::get('g_lulusan');
			}
			else
			{		
				$l = \DB::select('SELECT SUBSTR(tglKeluar, 7, 4) as thLulus, COUNT(NIM) AS jumlah FROM mahasiswa WHERE tglKeluar IS NOT NULL and tglKeluar <> "" GROUP BY thLulus');
				$p = \DB::select('SELECT SUBSTR(NIM, 1, 4) AS tahun, COUNT(NIM) AS jumlah FROM mahasiswa GROUP BY tahun');
				foreach($l as $lu) $lulusan[$lu -> thLulus] = $lu -> jumlah;
				foreach($p as $pa) 
				{
					$per_angkatan[$pa -> tahun] = $pa -> jumlah;
					$lulusan[$pa -> tahun] = isset($lulusan[$pa -> tahun]) ? $lulusan[$pa -> tahun] : 0;
				}
				ksort($lulusan);
				
				Cache::put('g_per_angkatan', $per_angkatan, 60);
				Cache::put('g_lulusan', $lulusan, 60);
			}
			
			return view('home', compact('informasi', 'counter', 'per_angkatan', 'lulusan', 'status', 'prov', 'pk_ortu', 'jk_angkatan', 'mhs_prodi', 'jk_dosen', 'st_dosen', 'png_dosen', 'files'));
		}
		
	}

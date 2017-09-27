<?php namespace Siakad\Http\Middleware;
	
	use Closure;
	
	class CheckKuesioner{
		public function handle($request, Closure $next)
		{
			//pass another user type
			if(\Auth::user() -> role_id != 512) return $next($request);
			
			$config = config('custom.kuesioner');
			$now = strtotime('now');
			if($now < strtotime(toYmd($config['tgl-mulai'])) || $now > strtotime(toYmd($config['tgl-selesai']))) return $next($request);
			
			$mhs_id = \Auth::user() -> authable_id;			
			$result =  \Siakad\Nilai::getCompletedCourse() -> get();
			if($result -> count())
			// if($result)
			{
				if($this ->  checkKuesioner($mhs_id) > 0)
				{
					// check null
					if($this ->  checkNullKuesioner($mhs_id) > 0)
					{
						return redirect('/penilaian');	
					}
				}
				else
				{
					return redirect('/penilaian');	
				}
			}	
			return $next($request);
		}
		
		private function checkKuesioner($mhs_id)
		{
			/* $result = \DB::select("
				SELECT COUNT(*) AS poin
				FROM kuesioner_mahasiswa km 
				WHERE km.mahasiswa_id = :mhs_id AND km.matkul_tapel_id IN (
				SELECT mt.id 
				FROM matkul_tapel mt 
				INNER JOIN tapel 
				ON tapel.id = mt.tapel_id 
				WHERE tapel.aktif = 'y'
				)",
				['mhs_id' => $mhs_id]
			); */
			
			$result = \DB::select("
			SELECT COUNT(*) AS poin
			FROM kuesioner_mahasiswa km 
			WHERE km.mahasiswa_id = :mhs_id AND km.matkul_tapel_id IN (
			SELECT mt.id 
			FROM matkul_tapel mt 
			INNER JOIN tapel 
			ON tapel.id = mt.tapel_id 
			WHERE mt.tapel_id in (select tapel.id from tapel where tapel.nama2 < (select tapel.nama2 from tapel where tapel.aktif = 'y'))
			)",
			['mhs_id' => $mhs_id]
			);
			return $result[0] -> poin;
		}
		
		private function checkNullKuesioner($mhs_id)
		{
			/* $result = \DB::select("
				SELECT COUNT(*) AS isnull 
				FROM kuesioner_mahasiswa km 
				WHERE km.mahasiswa_id = :mhs_id AND km.skor IS NULL AND km.matkul_tapel_id IN (
				SELECT mt.id 
				FROM matkul_tapel mt 
				INNER JOIN tapel 
				ON tapel.id = mt.tapel_id 
				WHERE tapel.aktif = 'y'
				)",
				['mhs_id' => $mhs_id]
			); */
			$result = \DB::select("
			SELECT COUNT(*) AS isnull 
			FROM kuesioner_mahasiswa km 
			WHERE km.mahasiswa_id = :mhs_id AND km.skor IS NULL AND km.matkul_tapel_id IN (
			SELECT mt.id 
			FROM matkul_tapel mt 
			INNER JOIN tapel 
			ON tapel.id = mt.tapel_id 
			WHERE mt.tapel_id in (select tapel.id from tapel where tapel.nama2 < (select tapel.nama2 from tapel where tapel.aktif = 'y'))
			)",
			['mhs_id' => $mhs_id]
			);
			// dd($mhs_id);
			return $result[0] -> isnull;
		}
	}													
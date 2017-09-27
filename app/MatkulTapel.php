<?php
	
	namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class MatkulTapel extends Model
	{
		protected $guarded = [];
		protected $table = 'matkul_tapel';
		public $timestamps = false;
		
		public function scopeMatkulProdi($query, $prodi)
		{
			$query
			-> join('kurikulum_matkul', 'kurikulum_matkul.id', '=', 'matkul_tapel.kurikulum_matkul_id')
			-> join('matkul', 'kurikulum_matkul.matkul_id', '=', 'matkul.id')
			-> where('matkul_tapel.prodi_id', $prodi)
			-> orderBy('semester')
			-> distinct()
			-> select('matkul.nama');
		}
		public function scopeExportDataKelas($query, $prodi, $ta=null)
		{
			$query
			-> join('tapel', 'tapel_id', '=', 'tapel.id')
			-> join('kurikulum_matkul', 'kurikulum_matkul.id', '=', 'matkul_tapel.kurikulum_matkul_id')
			-> join('matkul', 'kurikulum_matkul.matkul_id', '=', 'matkul.id');
			
			if($ta == null) $query -> where('tapel.aktif', 'y');
			else $query -> where('tapel.id', $ta);
			
			$query
			-> where('matkul_tapel.prodi_id', $prodi)
			-> select('tapel.nama2 AS tapel', 'matkul.kode', 'matkul.nama', 'kurikulum_matkul.semester', 'kelas2 AS kelas');
		}
		
		
		public function scopeKelasKuliah($query, $ta = null, $prodi = null)
		{
			$query
			-> join('kurikulum_matkul', 'kurikulum_matkul.id', '=', 'matkul_tapel.kurikulum_matkul_id')
			-> join('matkul', 'kurikulum_matkul.matkul_id', '=', 'matkul.id')
			-> join('dosen', 'dosen.id', '=', 'matkul_tapel.dosen_id')
			-> join('tapel', 'tapel_id', '=', 'tapel.id')
			-> join('prodi', 'matkul_tapel.prodi_id', '=', 'prodi.id')
			-> join('kelas', 'matkul_tapel.kelas', '=', 'kelas.id')
			
			-> leftJoin('nilai', 'nilai.matkul_tapel_id', '=', 'matkul_tapel.id');
			// -> leftJoin('mahasiswa', 'nilai.mahasiswa_id', '=', 'mahasiswa.id');
			
			if($ta == null) $query -> where('tapel.aktif', 'y');
			else $query -> where('tapel.id', $ta);
			
			if($prodi != null) $query -> where('matkul_tapel.prodi_id', $prodi);
			
			$query			
			-> where(function($query){
				$query -> where('nilai.jenis_nilai_id', 0)
				-> orWhere('nilai.jenis_nilai_id', NULL);		
			}) 
			-> groupBy('matkul_tapel.id')
			-> groupBy('matkul_tapel.kelas')
			-> orderBy('kurikulum_matkul.semester')
			-> orderBy('kelas.id')
			
			/*
				-> select( 'matkul_tapel.kuota', 'tapel.nama AS tapel', 'matkul.nama AS matkul',
				'matkul.kode AS kode', 'dosen.nama AS dosen', 'matkul_tapel.semester',
				'matkul_tapel.sks', 'matkul_tapel.kelas2 AS kelas', 'prodi.singkatan AS prodi',
				'matkul_tapel.id AS mtid', 'kelas.nama AS program');
			*/
			/* -> select(\DB::raw('IFNULL(COUNT(mahasiswa.id), 0) AS `peserta`,
				`matkul_tapel`.`kuota`, `matkul_tapel`.`semester`, `matkul_tapel`.`sks`, `matkul_tapel`.`kelas2` as `kelas`, `matkul_tapel`.`id` as `mtid`, 
				`tapel`.`id` as `tapel_id`,
				`tapel`.`nama` as `tapel`,
				`tapel`.`nama2` as `nama_tapel2`,
				`matkul`.`nama` as `matkul`,`matkul`.`kode`,
				`dosen`.`nama` as `dosen`,
				`prodi`.`singkatan` as `prodi`,
			`kelas`.`nama` as `program`')); */
			
			-> select(\DB::raw('IFNULL(COUNT(mahasiswa_id), 0) AS `peserta`,
			`matkul_tapel`.`kuota`, `matkul_tapel`.`kelas2` as `kelas`, `matkul_tapel`.`id` as `mtid`, 
			(select count(nilai) from nilai where jenis_nilai_id = 0 and matkul_tapel_id = mtid and nilai <> "") nilai,
			`matkul`.`sks_total` as `sks`,
			`kurikulum_matkul`.`semester`,
			`tapel`.`id` as `tapel_id`,
			`tapel`.`nama` as `tapel`,
			`tapel`.`nama2` as `nama_tapel2`,
			`matkul`.`nama` as `matkul`,`matkul`.`kode`,
			`dosen`.`nama` as `dosen`,
			`prodi`.`singkatan` as `prodi`,
			`kelas`.`nama` as `program`'));
		}
		
		public function scopeMatkulAktif($query, $prodi = false)
		{
			$query
			// -> leftJoin('jadwal', 'jadwal.matkul_tapel_id', '=', 'matkul_tapel.id')
			-> join('kurikulum_matkul', 'kurikulum_matkul.id', '=', 'matkul_tapel.kurikulum_matkul_id')
			-> join('kurikulum', 'kurikulum.id', '=', 'kurikulum_matkul.kurikulum_id')
			-> join('matkul', 'kurikulum_matkul.matkul_id', '=', 'matkul.id')
			-> join('tapel', 'tapel_id', '=', 'tapel.id')
			-> join('prodi', 'matkul_tapel.prodi_id', '=', 'prodi.id')
			-> join('dosen', 'matkul_tapel.dosen_id', '=', 'dosen.id')
			-> join('kelas', 'matkul_tapel.kelas', '=', 'kelas.id')
			-> where('tapel.aktif', 'y');
			
			if($prodi) $query -> where('prodi.singkatan', \Auth::user() -> role -> sub);
			
			$query -> orderBy('matkul')
			-> select(
			'kurikulum.nama AS kur',
			'matkul.nama AS matkul', 'matkul.kode AS kd', 'matkul.sks_total AS sks', 
			'matkul_tapel.id as mtid', 'matkul_tapel.kelas2 AS kelas', 
			'kurikulum_matkul.semester',
			'prodi.singkatan AS prodi', 
			'dosen.nama AS dosen', 
			'kelas.nama AS program', 
			'kelas.id AS program_id');
		}
		
		public function scopeMataKuliahDosen($query, $dosen = null)
		{
			$query
			-> join('kurikulum_matkul', 'kurikulum_matkul.id', '=', 'matkul_tapel.kurikulum_matkul_id')
			-> join('matkul', 'kurikulum_matkul.matkul_id', '=', 'matkul.id')
			// -> leftJoin('jadwal', 'jadwal.matkul_tapel_id', '=', 'matkul_tapel.id')
			// -> join('ruang', 'ruang_id', '=', 'ruang.id')
			-> join('dosen', 'dosen_id', '=', 'dosen.id')
			-> join('tapel', 'tapel_id', '=', 'tapel.id')
			-> join('prodi', 'matkul_tapel.prodi_id', '=', 'prodi.id');
			if($dosen == null) 
			{
				$query -> where('dosen_id', \Auth::user() -> authable -> id) -> where('tapel.aktif', 'y');
			}
			else
			{
				$query -> where('dosen_id', $dosen);
			}
			// -> orderBy('hari') 
			// -> orderBy('jam_mulai') 
			$query -> select('tapel.nama AS ta', 'prodi.nama AS prodi', 'prodi.singkatan AS singk', 'prodi.strata AS strata', 'matkul.nama AS matkul', 'matkul.kode', 'matkul_tapel.*', 'matkul.sks_total as sks', 'kurikulum_matkul.semester');
		}
		
		public function scopeGetDataMataKuliah($query, $matkul_tapel_id)
		{
			$query 
			-> leftJoin('kurikulum_matkul', 'kurikulum_matkul.id', '=', 'matkul_tapel.kurikulum_matkul_id')
			-> leftJoin('matkul', 'kurikulum_matkul.matkul_id', '=', 'matkul.id')
			-> leftJoin('dosen', 'dosen_id', '=', 'dosen.id')
			-> leftJoin('tapel', 'matkul_tapel.tapel_id', '=', 'tapel.id')
			-> leftJoin('prodi', 'matkul_tapel.prodi_id', '=', 'prodi.id')
			-> leftJoin('jadwal', 'jadwal.matkul_tapel_id', '=', 'matkul_tapel.id')
			-> leftJoin('ruang', 'ruang_id', '=', 'ruang.id')
			-> join('kelas', 'matkul_tapel.kelas', '=', 'kelas.id')
			-> where('matkul_tapel.id', '=', $matkul_tapel_id)	
			-> select(
			'matkul.nama AS matkul', 'matkul.kode AS kd', 'matkul.id AS idmk',
			'matkul_tapel.*', 'matkul_tapel.kelas2 AS kelas', 'matkul_tapel.kelas AS program_id',
			'kurikulum_matkul.semester', 
			'dosen.nama AS dosen', 'dosen.id AS dosen_id', 'dosen.*', 
			'tapel.nama AS ta',
			'prodi.nama AS prodi', 'prodi.singkatan', 'prodi.kaprodi', 
			'jadwal.*', 
			'ruang.nama as ruang', 
			'kelas.nama AS program',
			'matkul.kode'
			);
		}
		
		public function jurnal()
		{
			return $this-> hasMany('Siakad\Jurnal');	
		}
		
		public function nilaiAkhir()
		{
			return $this-> hasMany('Siakad\Nilai') -> where('jenis_nilai_id', 0);	
		}
		
		public function mahasiswa()
		{
			return $this-> belongsToMany('Siakad\Mahasiswa', 'nilai') -> where('jenis_nilai_id', 0) -> orderBy('nama') ;	
		}
		
		public function prodi()
		{
			return $this -> belongsTo('Siakad\Prodi');
		}
		
		public function program()
		{
			return $this -> belongsTo('Siakad\Kelas', 'kelas');
		}
		
		public function ruang()
		{
			return $this -> belongsTo('Siakad\Ruang');
		}
		
		public function tapel()
		{
			return $this -> belongsTo('Siakad\Tapel');
		}
		
		public function dosen()
		{
			return $this -> belongsTo('Siakad\Dosen');
		}
		
		/* public function matkul()
		{
			return $this -> belongsTo('Siakad\Matkul');
		} */
	}

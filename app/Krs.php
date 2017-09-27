<?php
	
	namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class Krs extends Model
	{
   		protected $guarded = [];
		protected $table = 'krs';
		public $timestamps = false;
		
		public function scopeGetKRS($query, $mhs_id = null, $tapel_id = null)
		{
			$query
			-> join('tapel', 'tapel.id', '=', 'krs.tapel_id')
			-> join('krs_detail', 'krs.id', '=', 'krs_detail.krs_id')
			-> join('matkul_tapel', 'matkul_tapel.id', '=', 'krs_detail.matkul_tapel_id')
			-> join('kurikulum_matkul', 'kurikulum_matkul.id', '=', 'matkul_tapel.kurikulum_matkul_id')
			-> join('matkul', 'matkul.id', '=', 'kurikulum_matkul.matkul_id')
			-> join('dosen', 'dosen.id', '=', 'matkul_tapel.dosen_id')
			-> leftjoin('jadwal', 'jadwal.matkul_tapel_id', '=', 'matkul_tapel.id')
			-> leftjoin('ruang', 'jadwal.ruang_id', '=', 'ruang.id');
			
			if($mhs_id != null) $query -> where('krs.mahasiswa_id', $mhs_id);
			if($tapel_id != null) $query -> where('tapel.id', $tapel_id); else $query -> where('tapel.aktif', 'y');
			
			$query
			-> groupBy('matkul.nama')
			-> select(
			'krs.id AS krs_id',
			'matkul.nama AS nama_matkul',	'matkul.kode', 'matkul.sks_total AS sks',
			'matkul_tapel.id AS matkul_tapel_id', 'matkul_tapel.*',
			'ruang.nama AS ruangan',
			'jadwal.*',
			'dosen.nama AS dosen'
			);
		}
		/*
			public function scopeGetKRS($query, $id = null)
			{
			$query
			-> join('tapel', 'tapel.id', '=', 'krs.tapel_id')
			-> join('krs_detail', 'krs.id', '=', 'krs_detail.krs_id')
			-> join('matkul_tapel', 'matkul_tapel.id', '=', 'krs_detail.matkul_tapel_id')
			-> join('matkul', 'matkul.id', '=', 'matkul_tapel.matkul_id')
			-> join('dosen', 'dosen.id', '=', 'matkul_tapel.dosen_id')
			-> leftjoin('jadwal', 'jadwal.matkul_tapel_id', '=', 'matkul_tapel.id')
			-> where('tapel.aktif', 'y');
			if($id != null) $query -> where('krs.mahasiswa_id', $id);
			$query -> select('krs.id AS krs_id', 'matkul.nama AS nama_matkul', 'matkul.kode', 'matkul_tapel.id AS matkul_tapel_id', 'matkul_tapel.*', 'jadwal.*', 'dosen.nama as dosen');
			}
			*/
		
		public function detail()
		{
			return $this -> hasMany('Siakad\KrsDetail');
		}
		
		public function mahasiswa()
		{
			return $this -> belongsTo('Siakad\Mahasiswa');
		}
		public function tapel()
		{
			return $this -> belongsTo('Siakad\Tapel');
		}
	}

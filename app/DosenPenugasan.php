<?php namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class DosenPenugasan extends Model {
		protected $guarded = [];
		protected $table = 'dosen_penugasan';
		public $timestamps = false;
		
	 	public function ta_tugas()
		{
			return $this -> belongsTo('Siakad\Tapel', 'tapel_id');
		}
		public function prodi_tugas()
		{
			return $this -> belongsTo('Siakad\Prodi', 'prodi_id');
		} 
		
		public function scopeRiwayatPenugasan($query, $dosen=null, $penugasan=null, $prodi=null, $ta=null)
		{
			$query 
			-> leftJoin('dosen', 'dosen.id', '=', 'dosen_id')
			-> leftJoin('prodi', 'prodi.id', '=', 'prodi_id')
			-> leftJoin('tapel', 'tapel.id', '=', 'tapel_id')
			-> orderBy('tapel.nama', 'desc')
			-> orderBy('prodi.id');
			if($dosen !== null) $query -> where('dosen_id', $dosen);
			if($prodi !== null) $query -> where('prodi_id', $prodi);
			if($ta !== null) $query -> where('tapel_id', $ta);
			if($penugasan !== null) $query -> where('dosen_penugasan.id', $penugasan);
			$query
			-> select('dosen_penugasan.*', 'dosen.id as dosen_id', 'dosen.nama as dosen', 'dosen.NIDN', 'dosen.jenisKelamin', 'tapel.nama as tapel', 'prodi.strata', 'prodi.nama as prodi');
		}
	}

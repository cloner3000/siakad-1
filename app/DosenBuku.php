<?php namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class DosenBuku extends Model {
		protected $guarded = [];
		protected $table = 'dosen_buku';
		public $timestamps = false;
		
		public function scopeBuku($query, $dosen=null, $buku=null)
		{
			$query 
			-> leftJoin('dosen', 'dosen.id', '=', 'dosen_id')
			-> orderBy('tahun_terbit', 'desc');
			if($dosen !== null) $query -> where('dosen_id', $dosen);
			if($buku !== null) $query -> where('dosen_buku.id', $buku);
			$query
			-> select('dosen_buku.*', 'dosen_buku.id AS buku_id', 'dosen.id as dosen_id', 'dosen.nama as dosen', 'dosen.*');
		}
	}

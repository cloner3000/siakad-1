<?php
	
	namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class BiayaKuliah extends Model
	{
		protected $guarded = [];
		protected $table = 'setup_biaya';
		public $timestamps = false;
		public function scopeJenisPembayaran($query, $data)
		{
		 	$query
			-> join('jenis_biaya', 'jenis_biaya.id', '=', 'jenis_biaya_id')
			-> where('angkatan', $data['angkatan'])
			-> where('prodi_id', $data['prodi_id'])
			-> where('kelas_id', $data['program_id'])
			-> where('jenisPembayaran', $data['jenisPembayaran'])
			-> orderBy('jenis_biaya.nama')
			-> select('jenis_biaya.id', 'jenis_biaya.nama', 'jumlah AS tanggungan', 'jenis_biaya.id');
		}
		
		public function jenis()
		{
			return $this -> belongsTo('Siakad\JenisBiaya', 'jenis_biaya_id');	
		}
	}

<?php
	
	namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class Biaya extends Model
	{
		use \Sofa\Eloquence\Eloquence;
		protected $searchableColumns = [
		'mahasiswa.nama' => 8,
		'mahasiswa.NIM' => 8,
		'pembayaran.nama' => 10
		];
		
		protected $guarded = [];
		protected $table = 'biaya';
		
		// public function scopeDaftarPembayaran($query, $mahasiswa_id = null, $tagihan = false)
		// {
		// $query 
		// -> join('mahasiswa', 'mahasiswa.id', '=', 'mahasiswa_id')
		// -> join('jenis_biaya', 'jenis_biaya.id', '=', 'jenis_biaya_id');
		
		// if($mahasiswa_id != null)
		// {
		// $query -> where('mahasiswa_id', $mahasiswa_id);
		// }
		
		// if($tagihan)
		// {
		// $query -> where('lunas', 'n');
		// }
		// /* 			else
		// {
		// $query -> where('lunas', 'y');
		// } */
		/* -> join('users', 'users.id', '=', 'user_id')
		-> select('mahasiswa.NIM AS nim', 'mahasiswa.nama AS nama', 'jenis_biaya.nama AS jenis', 'jenis_biaya.jumlah', 'biaya.*') */
		// $query 
		// -> orderBy('created_at', 'desc')
		// -> select(\DB::raw('mahasiswa.NIM AS nim, mahasiswa.id AS mhs_id, mahasiswa.nama AS nama, jenis_biaya.jangka AS jangka, jenis_biaya.nama AS jenis, jenis_biaya.jumlah, biaya.*, DATE_FORMAT(biaya.created_at,"%d %b %y") AS tanggal'));
		// }
		
		// public function scopeDetailPembayaran($query, $nim)
		// {
		// $query 
		// -> join('mahasiswa', 'mahasiswa.id', '=', 'mahasiswa_id')
		// -> join('jenis_biaya', 'jenis_biaya.id', '=', 'jenis_biaya_id')
		// -> where('mahasiswa.NIM', $nim)
		// -> where('lunas', 'y')
		// -> orderBy('updated_at', 'desc')
		// -> select(\DB::raw('mahasiswa.NIM AS nim, mahasiswa.id AS mhs_id, mahasiswa.nama AS nama, jenis_biaya.jangka AS jangka, jenis_biaya.nama AS jenis, jenis_biaya.jumlah, biaya.*, DATE_FORMAT(biaya.updated_at,"%d %b %y") AS tanggal'));
		// }
		
		public function transInfo()
		{
			return $this -> morphMany('\Siakad\Neraca', 'transable');
		}
		
		public function mahasiswa()
		{
			return $this -> belongsTo('\Siakad\Mahasiswa', 'mahasiswa_id', 'id');
		}
		
		public function petugas()
		{
			return $this -> belongsTo('\Siakad\User', 'user_id', 'id');
		}
		
		public function jenis()
		{
			return $this -> belongsTo('Siakad\JenisBiaya', 'jenis_biaya_id');	
		}
		// public function tapel()
		// {
		// return $this -> belongsTo('\Siakad\tapel');
		// }
	}

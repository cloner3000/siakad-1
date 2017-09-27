<?php namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class Skripsi extends Model 
	{
		use \Sofa\Eloquence\Eloquence;
		protected $searchableColumns = [
		'judul' => 8,
		'abstrak' => 7
		];
		protected $guarded = [];
		protected $table = 'skripsi';
		public $timestamps = false;
		
		public function pengarang()
		{
			return $this -> hasOne('Siakad\Mahasiswa');		
		}
		
		public function pembimbing()
		{
			return $this -> belongsToMany('Siakad\Dosen', 'dosen_skripsi');
		}
		
		public function bimbingan()
		{
			return $this -> hasMany('Siakad\BimbinganSkripsi');
		}
	}

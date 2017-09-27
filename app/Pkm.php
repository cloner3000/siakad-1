<?php namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class Pkm extends Model {
		protected $guarded = [];
		protected $table = 'pkm';
		
		public function peserta()
		{
			return $this -> hasMany('Siakad\Mahasiswa');		
		}
	}

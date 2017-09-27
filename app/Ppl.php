<?php namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class Ppl extends Model {
		protected $guarded = [];
		protected $table = 'ppl';
		
		public function peserta()
		{
			return $this -> hasMany('Siakad\Mahasiswa');		
		}
	}

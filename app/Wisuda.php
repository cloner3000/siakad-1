<?php namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class Wisuda extends Model {
		protected $guarded = [];
		protected $table = 'wisuda';

		public function scopeDataWisuda($query)
		{
			$query
			-> leftJoin('mahasiswa', 'mahasiswa.wisuda_id', '=', 'wisuda.id')
			-> groupBy('wisuda.id')
			-> orderBy('tanggal')
			-> select(\DB::raw('wisuda.*, count(NIM) as peserta'));
		}
		public function peserta()
		{
			return $this -> hasMany('Siakad\Mahasiswa');		
		}
	}

<?php
	
	namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class KurikulumMatkul extends Model
	{
   		protected $guarded = [];
		protected $table = 'kurikulum_matkul';
		public $timestamps = false;
		
		/* public function matkul()
		{
			return $this -> belongsTo('Siakad\Matkul');	
		}
		
		public function kurikulum()
		{
			return $this -> belongsTo('Siakad\Kurikulum');	
		} */
		
	}

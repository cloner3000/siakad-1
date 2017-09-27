<?php
	
	namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class Kurikulum extends Model
	{
   		protected $guarded = [];
		protected $table = 'kurikulum';
		public $timestamps = false;
		
		public function prodi()
		{
			return $this-> belongsTo('Siakad\Prodi');	
		}
		
		public function tapel()
		{
			return $this-> belongsTo('Siakad\Tapel', 'tapel_mulai');	
		}
		
		public function matkul()
		{
			return $this -> belongsToMany('Siakad\Matkul');	
		}
		
	}

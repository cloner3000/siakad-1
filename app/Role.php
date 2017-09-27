<?php
	
	namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class Role extends Model
	{
		protected $guarded = [];
		public $timestamps = false;
		
		public function users()
		{
			return $this->hasMany('Siakad\User', 'role_id', 'id');
		}
	}

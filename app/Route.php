<?php
	
	namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class Route extends Model
	{
		protected $table = 'routes';
		protected $guarded = [];
		
		public $timestamps = false;
	}

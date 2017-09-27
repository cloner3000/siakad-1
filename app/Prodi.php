<?php
	
	namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class Prodi extends Model
	{
		protected $guarded = [];
		protected $table = 'prodi';
		public $timestamps = false;
	}

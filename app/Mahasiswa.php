<?php
	
	namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class Mahasiswa extends Model
	{
		use \Sofa\Eloquence\Eloquence;
		protected $searchableColumns = [
		'NIM' => 8,
		'NIRM' => 8,
		'nama' => 10,
		'prodi.nama' => 7,
		'prodi.singkatan' => 7
		];
		protected $guarded = [];
		protected $table = 'mahasiswa';
		
		public function scopeDaftarMahasiswa($query, $filter = null)
		{
			$query
			-> join('prodi', 'mahasiswa.prodi_id', '=', 'prodi.id')
			-> join('kelas', 'mahasiswa.kelasMhs', '=', 'kelas.id')
			-> join('users', 'mahasiswa.id', '=', 'users.authable_id')
			-> leftJoin('krs', 'mahasiswa.id', '=', 'krs.mahasiswa_id')
			-> leftJoin('tapel', 'krs.tapel_id', '=', 'tapel.id')
			-> where('users.authable_type', 'Siakad\\Mahasiswa')
			-> where('tapel.aktif', 'y');
			
			if($filter != null and $filter['angkatan'] !== '-') $query -> where('angkatan', $filter['angkatan']);
			if($filter != null and $filter['semester'] !== '-') $query -> where('semesterMhs', $filter['semester']);
			if($filter != null and $filter['prodi'] !== '-') $query -> where('prodi_id', $filter['prodi']);
			if($filter != null and $filter['status'] !== '-') $query -> where('statusMhs', $filter['status']);
			if($filter != null and $filter['program'] !== '-') $query -> where('kelasMhs', $filter['program']);
			
			$query 
			-> orderBy('NIM', 'desc')
			-> select('NIM', 'NIRM', 'mahasiswa.id AS mhs_id', 'mahasiswa.nama AS nama', 'last_login', 'semesterMhs', 'singkatan', 'kelas.nama AS program', 'statusMhs', 'approved');
		}
		
		public function krs()
		{
			return $this -> hasMany('Siakad\Krs');	
		}
		
		public function kewarganegaraan()
		{
			return $this -> belongsTo('Siakad\Negara', 'wargaNegara', 'kode');	
		}
		
		public function skripsi()
		{
			return $this -> belongsTo('Siakad\Skripsi');	
		}
		
		public function ppl()
		{
			return $this -> belongsTo('Siakad\Ppl');	
		}
		
		public function pkm()
		{
			return $this -> belongsTo('Siakad\Pkm');	
		}
		
		public function wisuda()
		{
			return $this -> belongsTo('Siakad\Wisuda');	
		}
		
		public function wilayah_propinsi()
		{
			return $this -> belongsTo('Siakad\Propinsi', 'propinsi');	
		}
		
		public function nilai()
		{
			return $this->hasMany('Siakad\Nilai');	
		}
		
		public function kelas()
		{
			return $this -> belongsTo('Siakad\Kelas', 'kelasMhs');
		}
		
		public function prodi()
		{
			return $this -> belongsTo('Siakad\Prodi');
		}
		
		public function dosenwali()
		{
			return $this -> belongsTo('Siakad\Dosen', 'dosen_wali');
		}
		
		public function authInfo()
		{
			return $this -> morphOne('\Siakad\User', 'authable');
		}
		
		public function kuesioner()
		{
			return $this -> belongsToMany('Siakad\Kuesioner');	
		}
		
		public function matkul_tapel()
		{
			return $this-> belongsToMany('Siakad\MatkulTapel', 'nilai', 'mahasiswa_id', 'matkul_tapel_id');	
		}
	}

<?php namespace Siakad;
	
	use Illuminate\Database\Eloquent\Model;
	
	class Nilai extends Model {
		
		protected $guarded = [];
		protected $table = 'nilai';
		
		
		//transkrip merge
		public function scopeTranskripMerge($query, $prodi, $angkatan = null)
		{
			$query
			-> join('matkul_tapel', 'matkul_tapel_id', '=', 'id')
			-> join('kurikulum_matkul', 'kurikulum_matkul.id', '=', 'matkul_tapel.kurikulum_matkul_id')
			-> join('matkul', 'kurikulum_matkul.matkul_id', '=', 'matkul.id')
			-> join('tapel', 'tapel.id', '=', 'matkul_tapel.tapel_id')
			-> join('mahasiswa', 'mahasiswa.id', '=', 'nilai.mahasiswa_id')
			-> leftJoin('skripsi', 'mahasiswa.skripsi_id', '=', 'skripsi.id')
			-> where('jenis_nilai_id', 0)
			-> where('mahasiswa.prodi_id', $prodi);
			
			if($angkatan != null) $query -> where('mahasiswa.angkatan', $angkatan);
			
			// -> whereNotNull('nilai.nilai')
			$query
			-> orderBy('mahasiswa.NIM')
			// -> orderBy('semester')
			-> select(
			'mahasiswa.id', 'mahasiswa.nama', 'mahasiswa.tmpLahir', 'mahasiswa.tglLahir', 'NIM', 'NIRM', 'NIRL1', 'NIRL2',
			'matkul.nama as matkul', 'matkul.sks_total as sks', 
			'nilai.nilai',
			'skripsi.judul'
			);
		}
		
		//transkrip
		public function scopeTranskrip($query, $id)
		{
			$query
			-> join('matkul_tapel', 'matkul_tapel_id', '=', 'id')
			-> join('kurikulum_matkul', 'kurikulum_matkul.id', '=', 'matkul_tapel.kurikulum_matkul_id')
			-> join('matkul', 'kurikulum_matkul.matkul_id', '=', 'matkul.id')
			-> join('tapel', 'tapel.id', '=', 'matkul_tapel.tapel_id')
			-> where('jenis_nilai_id', 0)
			-> where('mahasiswa_id', $id)
			-> whereNotNull('nilai.nilai')
			-> groupBy('matkul_tapel.id')
			-> orderBy('semester')
			-> select('matkul.nama as matkul', 'matkul.kode', 'matkul.sks_total as sks', 'kurikulum_matkul.semester', 'tapel.nama as tapel', 'nilai.nilai');
		}
		
		// KUESIONER
		public function scopeGetCompletedCourse($query)
		{			
			$query
			-> leftJoin('matkul_tapel', 'matkul_tapel_id', '=', 'id')
			
			-> leftJoin('kurikulum_matkul', 'kurikulum_matkul.id', '=', 'matkul_tapel.kurikulum_matkul_id')
			-> leftJoin('matkul', 'kurikulum_matkul.matkul_id', '=', 'matkul.id')
			
			// -> leftJoin('matkul', 'matkul_id', '=', 'matkul.id')
			-> leftJoin('dosen', 'dosen_id', '=', 'dosen.id')
			-> leftJoin('tapel', 'tapel.id', '=', 'matkul_tapel.tapel_id')
			-> leftJoin('prodi', 'matkul_tapel.prodi_id', '=', 'prodi.id')
			-> leftJoin('kelas', 'matkul_tapel.kelas', '=', 'kelas.id')
			-> where('jenis_nilai_id', 0)
			-> whereRaw('matkul_tapel.tapel_id IN (select tapel.id from tapel where tapel.nama2 < (select tapel.nama2 from tapel where tapel.aktif = "y"))')
			-> where('mahasiswa_id', \Auth::user() -> authable_id)
			
			->select('prodi.nama AS prodi', 'matkul.nama AS matkul', 'matkul.kode AS kd', 'dosen.nama AS dosen', 'matkul_tapel.id as mtid', 'matkul_tapel.*');
		}
		
		public function scopeGetJadwalMahasiswa($query, $all = false)
		{
			$query
			-> Join('matkul_tapel', 'matkul_tapel_id', '=', 'id')
			-> join('kurikulum_matkul', 'kurikulum_matkul.id', '=', 'matkul_tapel.kurikulum_matkul_id')
			-> join('matkul', 'kurikulum_matkul.matkul_id', '=', 'matkul.id')
			-> Join('prodi', 'matkul_tapel.prodi_id', '=', 'prodi.id')
			// -> Join('ruang', 'ruang_id', '=', 'ruang.id')
			-> Join('dosen', 'dosen_id', '=', 'dosen.id')
			-> Join('tapel', 'tapel.id', '=', 'matkul_tapel.tapel_id')
			
			-> where('jenis_nilai_id', 0)
			// -> where('nilai', '<>', '')
			-> where('tapel.aktif', 'y');
			
			if(!$all){ 
				$query 
				-> where('mahasiswa_id', \Auth::user() -> authable_id);
			}
			
			// -> orderBy('hari') 
			// -> orderBy('jam_mulai') 
			$query -> groupBy('matkul_tapel.id')
			->select('prodi.nama AS prodi', 'matkul.nama AS matkul', 'matkul.kode AS kd', 'dosen.nama AS dosen', 'matkul_tapel.id as mtid', 'matkul_tapel.*');
		}
		
		public function scopeDataKHS($query, $nim, $ta = null)
		{
			$query
			-> Join('mahasiswa', 'mahasiswa_id', '=', 'mahasiswa.id')
			-> Join('matkul_tapel', 'nilai.matkul_tapel_id', '=', 'matkul_tapel.id')
			-> join('kurikulum_matkul', 'kurikulum_matkul.id', '=', 'matkul_tapel.kurikulum_matkul_id')
			-> join('matkul', 'kurikulum_matkul.matkul_id', '=', 'matkul.id')
			-> Join('tapel', 'tapel.id', '=', 'matkul_tapel.tapel_id')
			-> Join('prodi', 'matkul_tapel.prodi_id', '=', 'prodi.id')
			-> Join('kelas', 'kelas.id', '=', 'mahasiswa.kelasMhs')
			// -> leftjoin('jadwal', 'jadwal.matkul_tapel_id', '=', 'matkul_tapel.id')
			-> where('mahasiswa.NIM', $nim)
			-> where('jenis_nilai_id', 0);
			
			if($ta != null) $query -> where('matkul_tapel.tapel_id', $ta);
			
			$query 
			-> orderBy('matkul.nama')
			-> groupBy('matkul_tapel.id')
			-> select(
			'kelas.nama AS program', 
			'mahasiswa.id AS mhsid',  'mahasiswa.semesterMhs', 'NIM', 'NIRM', 'mahasiswa.nama AS mhs', 
			'prodi.nama as prodi', 'prodi.kaprodi', 'kelas2', 'kode', 
			'matkul.nama AS matkul', 'matkul.sks_total AS sks', 
			'kurikulum_matkul.semester AS smt', 
			'tapel.*', 'nilai'
			);
		}
		
		public function scopeGetNilaiAkhirMahasiswa($query, $matkul_tapel_id)
		{
			$query
		-> Join('mahasiswa', 'mahasiswa_id', '=', 'mahasiswa.id')
		-> Join('matkul_tapel', 'matkul_tapel_id', '=', 'matkul_tapel.id')
		-> where('jenis_nilai_id', 0)
		-> where('matkul_tapel.id', '=', $matkul_tapel_id)
		-> orderBy('NIM')
		-> select('NIM', 'nama', 'nilai', 'mahasiswa.id AS mhs_id');
		}
		
		public function mahasiswa()
		{
		return $this -> belongsTo('Siakad\Mahasiswa');	
		}
		
		public function jenis()
		{
		return $this -> belongsTo('Siakad\JenisNilai');	
		}
		}
				
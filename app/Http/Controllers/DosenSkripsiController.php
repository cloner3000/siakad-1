<?php
	
	namespace Siakad\Http\Controllers;
	
	use Redirect;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Input;
	
	use Siakad\Dosen;
	use Siakad\DosenSkripsi;
	use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	class DosenSkripsiController extends Controller
	{
/* 		use \Siakad\MahasiswaTrait;
		public function store($dosen_id)
		{
			$id = Input::get('id');
			if(count($id) > 0) foreach($id as $i) $data[] = '(' . $dosen_id . ', ' . $i . ')';
			$query = 'INSERT INTO dosen_skripsi (dosen_id, skripsi_id) VALUES ' . implode(', ', $data);
			$result = \DB::insert($query);
			if($result) return \Response::json(['success' => true]);
			return \Response::json(['success' => false]);
		} */
		public function store($dosen_id)
		{
			$data['dosen_id'] = $dosen_id;
			$data['skripsi_id'] = Input::get('skripsi_id');
			DosenSkripsi::insert($data);
			return Redirect::route('dosen.skripsi.mahasiswa', $dosen_id) -> with('success', 'Mahasiswa berhasil ditambahkan.');			
		}
		/* public function add($dosen_id)
			{
			$dosen = Dosen::find($dosen_id);
			
			$angkatan = \DB::select('
			SELECT DISTINCT `angkatan` AS `tahun` FROM `mahasiswa` ORDER BY `tahun` DESC
			');
			foreach($angkatan as $k => $v) $angkatans[$v->tahun] = $v->tahun;
			
			$angkatan = Input::get('angkatan') === null ? array_values($angkatans)[0] : Input::get('angkatan');
			
			$query = "
			SELECT d.nama as pembimbing, d.id, NIM, m.nama as mahasiswa, s.id as s_id, judul
			FROM skripsi s
			LEFT JOIN dosen_skripsi ds on ds.skripsi_id = s.id
			INNER JOIN mahasiswa m on s.id = m.skripsi_id
			LEFT JOIN dosen d on d.id = ds.dosen_id
			WHERE (ds.skripsi_id IS NULL OR ds.skripsi_id NOT IN (SELECT ds.skripsi_id FROM dosen_skripsi ds WHERE ds.dosen_id = :dosen_id)) AND m.angkatan = :angkatan
			";
			$data = \DB::select($query, ['dosen_id' => $dosen_id, 'angkatan' => $angkatan]);
			
			$tmp = [];
			foreach($data as $d)
			{
			if(isset($tmp[$d -> NIM])) {
			$existing = $tmp[$d -> NIM]['pembimbing'];
			$tmp[$d -> NIM]['pembimbing'] = '';
			$tmp[$d -> NIM]['pembimbing'] = array_merge($existing, [$d -> pembimbing]); continue;
			}
			$tmp[$d -> NIM] = [
			'nama' => $d -> mahasiswa,
			'judul' => $d -> judul,
			'id' => $d -> s_id,
			'pembimbing' => [$d -> pembimbing]
			];
			}
			$mahasiswa = $tmp;
			return view('dosen.skripsi.add', compact('dosen', 'mahasiswa', 'angkatans', 'angkatan'));
		} */
		public function index($dosen_id)
		{
			$dosen = Dosen::find($dosen_id);
			
			$query_calon = "
			SELECT d.nama as pembimbing, d.id, NIM, m.nama as mahasiswa, m.id as m_id, s.id as s_id, judul
			FROM skripsi s
			LEFT JOIN dosen_skripsi ds on ds.skripsi_id = s.id
			INNER JOIN mahasiswa m on s.id = m.skripsi_id
			LEFT JOIN dosen d on d.id = ds.dosen_id
			WHERE (ds.skripsi_id IS NULL OR ds.skripsi_id NOT IN (SELECT ds.skripsi_id FROM dosen_skripsi ds WHERE ds.dosen_id = :dosen_id))
			";
			$calon = \DB::select($query_calon, ['dosen_id' => $dosen_id]);
			foreach($calon as $c) $tmp[$c -> s_id] = $c -> mahasiswa . ' - ' . $c -> NIM;
			$calon = $tmp;
			
			$data = \DB::select('
			select 
			NIM, m.nama as mhs, m.skripsi_id,
			p.nama as prodi, p.strata, 
			k.nama as program, judul,  
			(select min(tglBimbingan) from bimbingan_skripsi where skripsi_id = m.skripsi_id) as tglAwal,
			(select max(tglBimbingan) from bimbingan_skripsi where skripsi_id = m.skripsi_id) as tglAkhir
			from dosen_skripsi ds
			left join dosen d on d.id = ds.dosen_id
			left join mahasiswa m on m.skripsi_id = ds.skripsi_id
			left join skripsi s on s.id = ds.skripsi_id
			left join prodi p on p.id = m.prodi_id
			left join kelas k on k.id = m.kelasMhs
			left join bimbingan_skripsi bs on bs.skripsi_id = s.id
			where d.id = :id
			group by s.id;', 
			['id' => $dosen_id]
			);
			return view('dosen.skripsi.mahasiswa', compact('data', 'dosen', 'calon'));
		}
		public function destroy($dosen_id, $skripsi_id)
		{
			\Siakad\DosenSkripsi::where('dosen_id', $dosen_id) -> where('skripsi_id', $skripsi_id) -> delete();
			return Redirect::route('dosen.skripsi.mahasiswa', $dosen_id) -> with('success', 'Mahasiswa berhasil dihapus.');
		}
	}

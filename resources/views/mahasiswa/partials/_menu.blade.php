@if($role_id === 4)
<a href="{{ route('biaya.create', $mahasiswa -> id) }}" class="btn btn-info btn-xs btn-flat">Pembayaran</a>
@endif
@if(in_array($role_id, [1,2]))
<a href="{{ route('mahasiswa.krs', $mahasiswa -> NIM) }}" class="btn btn-info btn-xs btn-flat">KRS</a>
<a href="{{ route('mahasiswa.khs', $mahasiswa -> NIM) }}" class="btn btn-info btn-xs btn-flat">KHS</a>
<a href="{{ route('mahasiswa.transkrip', $mahasiswa -> id) }}" class="btn btn-info btn-xs btn-flat" title="Mata Kuliah yang sudah ditempuh">Transkrip Nilai</a>
@if($mahasiswa -> skripsi_id != null)
<a href="{{ route('skripsi.show', $mahasiswa -> skripsi_id) }}" class="btn btn-info btn-xs btn-flat">Skripsi</a>
@endif
@endif
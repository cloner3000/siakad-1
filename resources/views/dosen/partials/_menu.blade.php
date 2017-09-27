<a href="{{ route('dosen.penugasan', $dosen -> id) }}" class="btn btn-info btn-xs btn-flat"><i class="fa fa-mouse-pointer"></i> Penugasan</a>
<a href="{{ route('dosen.aktifitasmengajar', $dosen -> id) }}" class="btn btn-info btn-xs btn-flat"><i class="fa fa-rss"></i> Aktifitas Mengajar</a>
<a href="" class="btn btn-info btn-xs btn-flat"><i class="fa fa-paw"></i> Riwayat Fungsional</a>
<a href="{{ route('dosen.pendidikan', $dosen -> id) }}" class="btn btn-info btn-xs btn-flat"><i class="fa fa-paw"></i> Riwayat Pendidikan</a>
<a href="{{ route('dosen.jurnal', $dosen -> id) }}" class="btn btn-info btn-xs btn-flat"><i class="fa fa-keyboard-o"></i> Jurnal</a>
<a href="" class="btn btn-info btn-xs btn-flat"><i class="fa fa-paw"></i> Riwayat Penelitian</a>
<a href="{{ route('dosen.skripsi.mahasiswa', $dosen -> id) }}" class="btn btn-info btn-xs btn-flat"><i class="fa fa-group"></i> Mahasiswa Bimbingan</a>
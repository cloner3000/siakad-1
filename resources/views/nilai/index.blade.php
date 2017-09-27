@extends('app')

@section('title')
Nilai Perkuliahan - {{ $data -> matkul }} - {{ $data -> ta }}
@endsection

@section('styles')
<style>
	.form-group{margin-bottom:0px;}
	.form-group > label{text-align:left !important}
	
	.inline{
	display: inline-block;
	width:70px;
	}
	.loader{
	color: #f00900;
	position: absolute;
	left: 30px;
	top: 10px;
	margin-left: 5px;
	}
	.table > thead > tr > th {
	vertical-align: middle;
	text-align: center;
	}
	.table.keterangan{
	text-align: center;
	}
	.btn-delete-type{
	cursor: pointer;
	}
	.btn-delete-type:hover{
	color: #c0302c;
	}
	.btn-delete-type:active, .btn-delete-type:focus {
	color: #f94f3a;
	}
	@if($data -> sync == 'y')
	.btn-calc{
	display:none;
	}
	@endif
	/*!
	* Hover.css (http://ianlunn.github.io/Hover/)
	* Version: 2.0.2
	* Author: Ian Lunn @IanLunn
	* Author URL: http://ianlunn.co.uk/
	* Github: https://github.com/IanLunn/Hover
	
	* Made available under a MIT License:
	* http://www.opensource.org/licenses/mit-license.php
	
	* Hover.css Copyright Ian Lunn 2014. Generated with Sass.
	*/
	/* Pulse */
	@-webkit-keyframes hvr-pulse {
	25% {
    -webkit-transform: scale(1.1);
    transform: scale(1.1);
	}
	
	75% {
    -webkit-transform: scale(0.9);
    transform: scale(0.9);
	}
	}
	
	@keyframes hvr-pulse {
	25% {
    -webkit-transform: scale(1.1);
    transform: scale(1.1);
	}
	
	75% {
    -webkit-transform: scale(0.9);
    transform: scale(0.9);
	}
	}
	
	.hvr-pulse {
	display: inline-block;
	vertical-align: middle;
	-webkit-transform: translateZ(0);
	transform: translateZ(0);
	box-shadow: 0 0 1px rgba(0, 0, 0, 0);
	-webkit-backface-visibility: hidden;
	backface-visibility: hidden;
	-moz-osx-font-smoothing: grayscale;
	}
	.hvr-pulse:hover, .hvr-pulse:focus, .hvr-pulse:active {
	-webkit-animation-name: hvr-pulse;
	animation-name: hvr-pulse;
	-webkit-animation-duration: 1s;
	animation-duration: 1s;
	-webkit-animation-timing-function: linear;
	animation-timing-function: linear;
	-webkit-animation-iteration-count: infinite;
	animation-iteration-count: infinite;
	}
	
	
	.box-import{
	display: none;
	}
</style>
@endsection

@push('scripts')
<script src="{{ asset('/js/clipboard.min.js') }}"></script>
<script>
	var clipboard = new Clipboard('.cbrd');
	clipboard.on('success', function(e) {
		alert('Data telah tersimpan di clipboard !');
	});
	
	$('.btn-import').click(function(){
		$('.box-import').toggle();
	});
</script>
@endpush

@section('header')
<section class="content-header">
	<h1>
		Perkuliahan
		<small>Input Nilai Perkuliahan</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/matkul/tapel') }}">Kelas Kuliah</a></li>
		<li class="active">Nilai Perkuliahan</li>
	</ol>
</section>
@endsection

@section('content')
@if(isset($error))
<div class="alert alert-danger">
	<strong>Error!</strong> Semester tidak aktif
</div>
@else
<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Mata Kuliah</h3>
		<div class="box-tools">
			@if(Auth::user() -> role_id <= 2)
			<button class="btn btn-info btn-xs btn-flat btn-import"><i class="fa fa-cloud-download"></i> Impor data</button>
			@endif
			@if(Auth::user() -> role_id == 128)
			<a href="{{ url('/kelaskuliah/' . $matkul_tapel_id . '/peserta') }}" class='btn btn-primary btn-flat btn-xs' title='Peserta'><i class='fa fa-group'></i></a>
			<a href='/kelaskuliah/{{ $matkul_tapel_id}}/jurnal' class='btn btn-warning btn-flat btn-xs' title='Jurnal'><i class='fa fa-book'></i></a>
			<a href="/kelaskuliah/{{ $matkul_tapel_id}}/absensi" class="btn btn-danger btn-flat btn-xs" title="Absensi"><i class="fa fa-font"></i></a>
			@else
			<a href="{{ url('/matkul/tapel/' . $matkul_tapel_id . '/mahasiswa') }}" class='btn btn-primary btn-flat btn-xs' title='Peserta'><i class='fa fa-group'></i> Peserta Kuliah</a>
			@endif
		</div>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-xs-12">
				<table width="100%">
					<tr>
						<th width="20%">Matakuliah & Semester</th><th width="2%">:</th><td width="30%">{{ $data -> matkul }} ({{ $data -> kd }}) ({{ $data -> semester }})</td>
						<th width="20%">Dosen</th><th width="2%">:</th><td>{{ $data -> dosen }}</td>
					</tr>
					<tr>
						<th>Program & Kelas</th><th>:</th><td>{{ $data -> program }} @if(isset($data -> kelas)) ({{ $data -> kelas }})@endif</td>
						<th>PRODI</th><th>:</th><td>{{ $data -> prodi }} ({{ $data -> singkatan }})</td>
					</tr>
					<tr>
						<th>Jadwal & Ruang</th><th>:</th><td>@if(isset($data -> hari)){{ config('custom.hari')[$data -> hari] }}, {{ $data -> jam_mulai }} - {{ $data -> jam_selesai }} ({{ $data -> ruang }})@else<span class="text-muted">Belum ada jadwal</span>@endif</td>
						<th>Tahun Akademik</th><th>:</th><td>{{ $data -> ta }}</td>
					</tr>
					<tr>
						<th>Jumlah Mahasiswa</th><th>:</th><td>{{ count($jenis_nilai) }}</td>
						<th></th><th></th><td></td>
					</tr>
				</table>
			</div>
		</div>		
	</div>
</div>
<div class="box box-info box-import">
	<div class="box-header with-border">
		<h3 class="box-title">Impor Data</h3>
	</div>
	<div class="box-body">
		{!! Form::model(new Siakad\Nilai, ['class' => 'form-horizontal', 'role' => 'form', 'route' => ['matkul.tapel.nilai.import']]) !!}
		<div class="form-group">
			<label for="data" class="col-sm-1 control-label">Data:</label>
			<div class="col-sm-5">
				{!! Form::hidden('matkul_tapel_id', $matkul_tapel_id) !!}
				{!! Form::textarea('data', null, array('class' => 'form-control', 'rows' => '6', 'placeholder' => 'nim_1;jenis_nilai_1;nilai_1|nim_2;jenis_nilai_2;nilai_2|nim_n;jenis_nilai_n;nilai_n' )) !!}
			</div>
		</div>
		<br/>
		<div class="form-group">
			<div class="col-sm-offset-1 col-sm-10">
				<button class="btn btn-flat btn-primary" type="submit"><i class="fa fa-cloud-download"></i> Import</button>
			</div>		
		</div>	
		{!! Form::close() !!}
	</div>
</div>

@if(count($jenis_nilai) < 1)
<div class="callout callout-danger">
	<h4>Kesalahan</h4>
	Belum ada mahasiswa yang terdaftar pada Mata Kuliah ini. Harap hubungi Administrator / Bagian Akademik
</div>
@else
<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Nilai</h3>
		<div class="box-tools">
			<button class="btn btn-success btn-xs btn-flat cbrd" data-clipboard-text="{!! $edata !!}"><i class="fa fa-cloud-upload"></i> Ekspor data</button>
			<button class="btn btn-warning btn-flat btn-xs" data-toggle="modal" data-target="#keterangan"><i class="fa fa-bolt"></i> Keterangan Penilaian</button>
			<button class="btn btn-info btn-flat btn-xs" data-toggle="modal" data-target="#petunjuk"><i class="fa fa-lightbulb-o"></i> Petunjuk Pengisian Nilai</button>
			<a href="{{ route('jenisnilai.index', $matkul_tapel_id) }}" class="btn btn-primary btn-xs btn-flat"><i class="fa fa-plus"></i> Pilih / input Komponen Penilaian</a>
			<a href="{{ url('/matkul/tapel/'. $matkul_tapel_id . '/nilai/cetak') }}" class="btn btn-xs btn-success btn-flat" title="Cetak form Nilai" target="_blank"><i class="fa fa-print"></i> Cetak form Nilai</a>
		</div>
	</div>
	<div class="box-body">
		<?php $n = 1; ?>
		<table class="table table-bordered" id="nilais">
			<thead>
				<tr>
					<th width="50px">No.</th>
					<th width="100px">NIM</th>
					<th>Nama</th>
					<?php
						$sid = current(array_keys($jenis_nilai));
						$gtype = current(array_keys($jenis_nilai[$sid]));
						krsort($jenis_nilai[$sid]);
					?>
					@foreach($jenis_nilai[$sid] as $t => $data)
					@if($data['jenis_nilai'] == 0)
					<th>Hasil Akhir</th>
					@else
					<th>{{ $data['nama_nilai'] }}&nbsp;<i id="{{ $data['mt_id'] }}-{{ $data['jenis_nilai'] }}" class="hvr-pulse fa fa-times-circle-o btn-delete-type"></i></th>
					@endif
					@endforeach
				</tr>
			</thead>
			<tbody>
				@foreach($jenis_nilai as $mahasiswa)
				<tr>
					<td>{{ $n }}</td>
					<td>{{ $mahasiswa[$gtype]['nim'] }}</td>
					<td>{{ $mahasiswa[$gtype]['nama']}}</td>
					<?php krsort($mahasiswa); ?>
					@foreach($mahasiswa as $nilai)
					<td>
						<?php if($nilai['jenis_nilai'] == 0) {$class=''; } else {$class='nilai_div'; }?>
						<div class="col-xs-12 {{ $class }}" id="{{ $nilai['id_mhs'] }}-{{ $nilai['mt_id'] }}-{{ $nilai['jenis_nilai'] }}">
							<?php 
								if($nilai['jenis_nilai'] == 0)
								{ 
									echo $nilai['nilai']; 
								} 
								else 
								{
									if(isset($nilai['nilai'])) 
									{
										echo $nilai['nilai'];	
									}
									else
									{
										echo '<input type="number" min="0" max="100" step="10" class="form-control nilai inline" />';
										echo '<select class="form-control nilai_huruf inline">';
										echo '<option value="-">-</option>';
										foreach(config('custom.nilai') as $nilai) echo '<option value="' . $nilai . '">' . $nilai . '</option>';
										echo '</select>';
									}
								}
							?>
						</div>
					</td>
					@endforeach
				</tr>
				<?php $n++; ?>
				@endforeach
			</tbody>
		</table>
		<br/>
		<div class="col-sm-offset-5">
			<a href="{{ route('matkul.tapel.hitungnilai', $matkul_tapel_id) }}" class="btn btn-success btn-flat btn-calc"><i class="fa fa-calculator"></i> Hitung hasil akhir</a>
		</div>
		<br/>
	</div>
</div>

<div class="modal fade" id="keterangan" tabindex="-1" role="dialog" aria-labelledby="keterangan-title" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="petunjuk-title"><strong>Keterangan Penilaian</strong></h4>
			</div>
			<div class="modal-body">
				<table class="table table-bordered keterangan">
					<thead>
						<tr>
							<th colspan="2">Angka</th>
							<th rowspan="2">Huruf</th>
							<th rowspan="2">Keterangan</th>
						</tr>
						<tr>
							<th>Interval</th>
							<th>Interval</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$base = config('custom.nilai');
							$konv = config('custom.konversi_nilai');
							$c = 0;
							foreach($base as $b)
							{
								if($b != 'D') $a[$b] = '<td>' . ($konv['base_100'][$base[$c + 1]] + 1) . ' - ' . $konv['base_100'][$b] . '</td>
								<td>' . ($konv['base_4'][$base[$c + 1]] + 0.01) . ' - ' . $konv['base_4'][$b] . '</td>
								<td>' . $b . '</td>
								<td>' . $konv['base_lulus'][$b] . '</td>';
								$c++;
							}
						?>
						@foreach($a as $td)
						<tr>
							{!! $td !!}
						</tr>
						@endforeach
						<tr>
							<td>&lt; 50</td>
							<td>&lt; 1.75</td>
							<td>D</td>
							<td>Tidak Lulus</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
</div>

<div class="modal fade" id="petunjuk" tabindex="-1" role="dialog" aria-labelledby="petunjuk-title" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="petunjuk-title"><strong>Petunjuk Pengisian Nilai</strong></h4>
			</div>
			<div class="modal-body">
				<ol style="padding-left: 18px;">
					<li>Klik <button class="btn btn-primary btn-xs btn-flat"><i class="fa fa-plus"></i> Pilih / input Komponen Penilaian</button></li>
					<li>
						Jika Komponen Penilaian yang diinginkan sudah terdaftar klik <button class="btn btn-primary btn-xs btn-flat"><i class= "fa fa-check"></i> Pilih</button>, 
						jika belum klik <button class="btn btn-info btn-xs btn-flat"><i class="fa fa-plus"></i> Buat Komponen Penilaian baru</button>, kemudian isikan nama dan bobot nilai yang diinginkan.
						Komponen Penilaian menentukan prosentase bobot nilai tersebut.
					</li>
					<li>Selanjutnya anda akan kembali ke tampilan halaman pengisian nilai dengan Komponen Penilaian yang telah anda pilih.</li>
					<li>Rentang nilai yang valid adalah 1 - 100.</li>
					<li>Isikan nilai pada kolom yang tepat, kemudian tekan tombol <button class="btn btn-default btn-xs btn-flat"><i class="fa fa-rotate-90 fa-level-down"></i> Enter</button> untuk menyimpan.</li>
					<li>Untuk mengubah nilai yang telah tersimpan, klik pada nilai yang ingin diubah. Ubah nilai, kemudian tekan tombol <button class="btn btn-default btn-xs btn-flat"><i class="fa fa-rotate-90 fa-level-down"></i> Enter</button> untuk menyimpan.</li>
					<li>Untuk menghitung Hasil Akhir, klik <button class="btn btn-success btn-xs btn-flat"><i class="fa fa-calculator"></i> Hitung hasil akhir</button></li>
					<li>Setiap anda melakukan perubahan nilai, anda harus meng-klik <button class="btn btn-success btn-xs btn-flat"><i class="fa fa-calculator"></i> Hitung hasil akhir</button> untuk melihat perubahan Hasil Akhir. Hal ini ditujukan untuk memperingan beban server.</li>
				</ol>
			</div>
		</div>
	</div>
</div>
@endif
@endif
@endsection

@push('scripts')
<script>
	$(document).on('click', '.btn-delete-type', function(){
		if(confirm('Hapus Komponen Penilaian?'))
		{
			var id = $(this).attr('id');
			var ida = id.split('-');
			$.ajax({
				type: "POST",
				data: {
					_token: "{{ csrf_token() }}",
					matkul_tapel_id: ida[0],
					jenis_nilai_id: ida[1]
				},
				url: '{{ route("matkul.tapel.nilai.destroy") }}',
				dataType: 'json',
				success: function(response) {
					if(response.success)
					{
						window.location.reload();
					}
				}
			});
		}
	});
	
	$('.nilai_div').on('click', function(){
		if($(this).children('.nilai').length < 1)
		{
			var nilai = $(this).text().trim();
			$(this).html('<input type="number" min="0" max="100" step="10" class="form-control nilai inline" value="'+nilai+'"/><select class="form-control nilai_huruf inline"><option value="-">-</option>@foreach(config('custom.nilai') as $nilai)<option value="{{ $nilai }}">{{ $nilai }}</option>@endforeach</select>');
		}
	});
	
	$(document).on('change', '.nilai_huruf', function(){
		var me = $(this);
		var target = me.prev('.nilai');
		
		e = $.Event('keydown');
		e.which = 13;
		
		var angka = 0;
		switch(me.val())
		{
			@foreach(config('custom.konversi_nilai.base_100') as $k => $v)case '{{ $k }}':
			angka = {{ $v }};
			break;
			@endforeach
		}
		target.val(angka).trigger(e);
	})
	
	$(document).on('keydown', '.nilai', function(e){
		console.log(e);
		if(e.which == 13)
		{			
			if($('.fa-spin').length) return;
			$(this).attr('disabled', true);
			$(this).after('<i class="fa fa-spinner fa-spin loader"></i>');
			var id = $(this).closest('.nilai_div').attr('id');
			var ida = id.split('-');
			$.ajax({
				type: "POST",
				data: {
					_token: "{{ csrf_token() }}",
					mahasiswa_id: ida[0],
					matkul_tapel_id: ida[1],
					jenis_nilai_id: ida[2],
					nilai: $(this).val()
				},
				url: '{{ route("matkul.tapel.nilai.store") }}',
				dataType: 'json',
				success: function(response) {
					if(response.success) 
					{
						$('#'+id).html(response.nilai);
						$('.btn-calc').show();
					}
					$('#nilais').find('input.nilai:first').focus();
				},
				error: function(XMLHttpRequest, textStatus, errorThrown){
					alert('Nilai bukan merupakan nilai yang valid. Masukkan nilai angka 0 - 100');
					$('.fa-spin').remove();					
					$(this).attr('disabled', false);
				}
			});	
		}
	});
</script>
@endpush																																																																																																																																																							
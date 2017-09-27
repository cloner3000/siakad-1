@extends('app')

@section('title')
Kartu Rencana Studi
@endsection

@section('styles')
<style>
	.checkbox{
	margin: 0px !important;
	padding-top: 0px !important;
	}
</style>
@endsection

@section('header')
<section class="content-header">
	<h1>
		Mahasiswa
		<small>Kartu Rencana Studi</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Kartu Rencana Studi</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Data Mahasiswa</h3>
	</div>
	<div class="box-body">
		<table>
			<tr>
				<th width="14%">Nama</th><td>:&nbsp;</td><td width="40%">{{ $mhs -> nama }}</td>
				<th width="29%">NIM</th><td>:&nbsp;</td><td>{{ $mhs -> NIM }}</td>
			</tr>
			<tr>
				<th>PRODI</th><td>:&nbsp;</td><td>{{ $mhs -> prodi -> nama }}</td>
				<th>Program</th><td>:&nbsp;</td><td>{{ $mhs -> kelas -> nama }}</td>
			</tr>
			<tr>
				<th>Semester</th><td>:&nbsp;</td><td>{{ $mhs -> semesterMhs }}</td>
				<th>Tahun Akademik</th><td>:&nbsp;</td><td>{{ $tapel -> nama }}</td>
			</tr>
			<tr>
				<th>Dosen PA</th><td>:&nbsp;</td><td>{{ $mhs -> dosenwali -> nama }}</td>
			<!--tr>
				<th>Status KRS</th><td>:&nbsp;</td><td>@if($status -> approved == 'y')<span class="text-success">Disetujui</span>@else<span class="text-danger">Pending</span>@endif</td>
			</tr-->
				<th>Batas akhir pengisian KRS</th><td>:&nbsp;</td><td>{{ formatTanggal($tapel -> selesaiKrs) }}</td>
			</tr>
		</table>
	</div>
</div>
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Kartu Rencana Studi</h3>
	</div>
	<div class="box-body">
		@if(!count($krs))
		@if(!$open)
		<div class="callout callout-danger">
			<h4>Error</h4>
			Waktu pengisian KRS Online sudah habis. Hubungi bagian Akademik jika anda belum melakukan KRS Online.
		</div>
		@else
		<p class="text-muted">Belum ada data Mata Kuliah. Klik <a href="{{ url('/tawaran') }}" class="btn btn-info btn-xs btn-flat" title="Input Data"><i class="fa fa-plus"></i> Tawaran Mata Kuliah</a> untuk memilih Mata Kuliah yang diinginkan</p>
		@endif
		@else
		<?php $c=1; ?>
		{!! Form::open(['class' => 'form-inline', 'method' => 'DELETE', 'route' => ['krs.destroy']]) !!}
		<table class="table table-bordered">
			<thead>
				<tr>
					<th width="20px">No</th>
					<th>Mata Kuliah</th>
					<th>Kelas</th>
					<th>SKS</th>
					<th>Kapasitas</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@foreach($krs as $g)
				<tr>
					<td>{{ $c }}</td>
					<td>
						@if($open)
						<div class="checkbox"><label>{!! Form::checkbox('matkul_tapel_id[]', $g -> mtid) !!} {{ $g -> nama_matkul }} ({{ $g -> kode }})</label></div>
						@else
						{{ $g -> nama_matkul }} ({{ $g -> kode }})
						@endif
					</td>
					<td>{{ $g -> kelas2 }}</td>
					<td>{{ $g -> sks }}</td>
					<td>{{ $g -> peserta }} / {{ $g -> kuota }}</td>
				</tr>
				<?php $c++; ?>
				@endforeach
			</tbody>
		</table>
		@if($open)
		<a href="{{ url('/tawaran') }}" class="btn btn-info btn-flat" title="Input Data"><i class="fa fa-plus"></i> Tambah</a>
		{!! Form::hidden('mahasiswa_id', $mhs -> id) !!}
		{!! Form::hidden('krs_id', $krs[0] -> krs_id) !!}
		<button class="btn btn-danger btn-flat" type="submit"><i class="fa fa-trash"></i> Hapus</button>
		@endif 
		<a href="{{ url('/krs/print') }}" class="btn btn-warning btn-flat" target="_blank"><i class="fa fa-print"></i> Cetak</a>
		{!! Form::close() !!}
		@endif
	</div>
</div>
@endsection																						
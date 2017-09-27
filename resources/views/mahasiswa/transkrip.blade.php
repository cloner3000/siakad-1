@extends('app')

@section('title')
Transkrip Nilai - {{ $mahasiswa -> nama }}
@endsection

@section('header')
<section class="content-header">
	<h1>
		Perkuliahan
		<small>Transkrip Nilai</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/mahasiswa') }}"> Mahasiswa</a></li>
		<li><a href="{{ route('mahasiswa.show', $mahasiswa -> id) }}"> {{ ucwords(strtolower($mahasiswa -> nama)) }}</a></li>
		<li class="active">Transkrip Nilai</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Data Mahasiswa</h3>
		<div class="box-tools">
			@include('mahasiswa.partials._menu', ['role_id' => \Auth::user() -> role_id, 'mahasiswa' => $mahasiswa])
		</div>
	</div>
	<div class="box-body">
		<table>
			<tr>
				<th>Nama</th><td>:&nbsp;</td><td>{{ $mahasiswa -> nama }}</td>
			</tr>
			<tr>
				<th>NIM</th><td>:&nbsp;</td><td>{{ $mahasiswa -> NIM }}</td>
			</tr>
			<tr>
				<th>PRODI</th><td>:&nbsp;</td><td>{{ $mahasiswa -> prodi -> strata }} {{ $mahasiswa -> prodi -> nama }}</td>
			</tr>
			<tr>
				<th>Program</th><td>:&nbsp;</td><td>{{ $mahasiswa -> kelas -> nama }}</td>
			</tr>
			<tr>
				<th>Semester</th><td>:&nbsp;</td><td>{{ $mahasiswa -> semesterMhs }}</td>
			</tr>
		</table>
	</div>
</div>

<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Transkrip Nilai</h3>
		<div class="box-tools">
			<a href="{{ route('mahasiswa.transkrip.print', $mahasiswa -> id) }}" class="btn btn-success btn-xs btn-flat" title="Print Transkrip"><i class="fa fa-print"></i> Cetak Transkrip</a>
		</div>
	</div>
	<div class="box-body">
		@if(!$data->count())
		<p class="text-muted">Belum ada data</p>
		@else
		<?php $c=1; ?>
		<table class="table table-bordered table-striped">
			<thead>
				<tr>
					<th>No.</th>
					<th>Kode MK</th>
					<th>Nama MK</th>
					<th>SKS</th>
					<th>Semester</th>
					<th>Tahun Akademik</th>
					<th>Nilai</th>
				</tr>
			</thead>
			<tbody>
				@foreach($data as $g)
				<tr>
					<td>{{ $c }}</td>
					<td>{{ $g -> kode }}</td>
					<td>{{ $g -> matkul }}</td>
					<td>{{ $g -> sks }}</td>
					<td>{{ $g -> semester }}</td>
					<td>{{ $g -> tapel }}</td>
					<td>{{ $g -> nilai }}</td>
				</tr>
				<?php $c++; ?>
				@endforeach
			</tbody>
		</table>
		@endif
	</div>
</div>
@endsection												
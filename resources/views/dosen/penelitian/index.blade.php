@extends('app')

@section('title')
Daftar Penelitian Dosen
@endsection

@section('header')
<section class="content-header">
	<h1>
		Penelitian Dosen
		<small>Daftar</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Daftar Penelitian Dosen</li>
	</ol>
</section>
@endsection

@section('content')
<style>
.table>thead>tr>th {
    text-align: center;
    vertical-align: middle;
}
</style>
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Daftar Penelitian Dosen</h3>
		<div class="box-tools">
			<a href="{{ route('dosen.penelitian.export') }}" class="btn btn-success btn-xs btn-flat" title="Export Data Penelitian Dosen"><i class="fa fa-file-excel-o"></i> Export Penelitian Dosen</a>
			<a href="{{ route('dosen.penelitian.create') }}" class="btn btn-primary btn-xs btn-flat" title="Input Data Penelitian Dosen"><i class="fa fa-plus"></i> Tambah Penelitian Dosen</a>
		</div>		
	</div>
	<div class="box-body">
		@if(!$penelitian->count())
		<p class="text-muted">Belum ada data</p>
		@else
		<?php $c=1; ?>
		<table class="table table-bordered table-striped">
			<thead>
				<tr>
					<th rowspan="2">No.</th>
					<th rowspan="2">Nama</th>
					<th rowspan="2">Tahun</th>
					<th rowspan="2">Judul Penelitian</th>
					<th rowspan="2">Jenis Penelitian</th>
					<th rowspan="2">Ketua Penelitian</th>
					<th colspan="4">Nilai Sumber Dana (Rp)</th>
					<th rowspan="2"></th>
				</tr>
				<tr>
					<th>Mandiri</th>
					<th>Lembaga</th>
					<th>Hibah Nasional</th>
					<th>Hibah Internasional</th>
				</tr>
			</thead>
			<tbody>
				@foreach($penelitian as $b)
				<tr>
					<td>{{ $c }}</td>
					<td>{{ $b -> dosen }}</td>
					<td>{{ $b -> tahun }}</td>
					<td>{{ $b -> judul }}</td>
					<td>@if($b -> jenis == 1) Pribadi @else Kelompok @endif</td>
					<td>{{ $b -> ketua_penelitian }}</td>
					<td>{{ number_format($b -> dana_pribadi, 2, ',', '.') }}</td>
					<td>{{ number_format($b -> dana_lembaga, 2, ',', '.') }}</td>
					<td>{{ number_format($b -> dana_hibah_nasional, 2, ',', '.') }}</td>
					<td>{{ number_format($b -> dana_hibah_internasional, 2, ',', '.') }}</td>
					<td>
						<a href="{{ route('dosen.penelitian.edit', $b -> penelitian_id) }}" class="btn btn-warning btn-flat btn-xs" title="Edit data penelitian"><i class="fa fa-pencil-square-o"></i> Eddit</a>
					</td>
				</tr>
				<?php $c++; ?>
			@endforeach
			</tbody>
			</table>
			@endif
			</div>
			</div>
			@endsection															
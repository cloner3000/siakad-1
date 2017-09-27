@extends('app')

@section('title')
Aktifitas Mengajar
@endsection

@section('header')
<section class="content-header">
	<h1>
		Dosen
		<small>Aktifitas Mengajar</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Aktifitas Mengajar</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Aktifitas Mengajar</h3>
	</div>
	<div class="box-body">
		<?php $c = 1; ?>
		<table class="table table-bordered table-hover table-striped">
			<thead>
				<tr>
					<th>No</th>
					<th>Periode</th>
					<th>Mata Kuliah</th>
					<th>Prodi</th>
					<th>Program</th>
					<th>Smt</th>
					<th>SKS</th>
					<th>Mhs</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@if(!$data -> count())
				<td colspan="8" align="center">Belum ada data</td>
				@else
				@foreach($data as $mk)
				<tr>
					<td>{{ $c }}</td>
					<td>{{ $mk -> ta }}</td>
					<td>{{ $mk -> matkul}} ({{ $mk -> kode }})</td>
					<td>{{ $mk -> strata }} {{ $mk -> prodi }}</td>
					<td>{{ $mk -> program -> nama }}</td>
					<td>{{ $mk -> semester }}</td>
					<td>{{ $mk -> sks }}</td>
					<td>{{ $mk -> mahasiswa -> count() }}</td>
					<td>
						<!--button type="button" class="btn btn-info btn-xs" data-container="body" data-toggle="popover" data-placement="auto top" data-content="
							<div class='btn-group'>
							<a href='/kelaskuliah/{{ $mk -> id}}/peserta' class='btn btn-xs btn-primary' title='Peserta'><i class='fa fa-group'></i></a>
							<a href='/kelaskuliah/{{ $mk -> id}}/jurnal' class='btn btn-xs btn-warning' title='Jurnal'><i class='fa fa-book'></i></a>
							<a href='/kelaskuliah/{{ $mk -> id}}/absensi' class='btn btn-xs btn-danger' title='Absensi'><i class='fa fa-font'></i></a>
							<a href='/kelaskuliah/{{ $mk -> id}}/nilai' class='btn btn-xs btn-success' title='Nilai'><i class='fa fa-bar-chart'></i></a>
							</div>
							">
							<i class="fa fa-link"></i>
						</button-->
						<a href='{{ url("/kelaskuliah/" . $mk -> id ."/peserta") }}' class='btn btn-xs btn-primary' title='Peserta'><i class='fa fa-group'></i></a>
						<a href='{{ url("/kelaskuliah/" . $mk -> id ."/jurnal") }}' class='btn btn-xs btn-warning' title='Jurnal'><i class='fa fa-book'></i></a>
						<a href='{{ url("/kelaskuliah/" . $mk -> id ."/absensi") }}' class='btn btn-xs btn-danger' title='Absensi'><i class='fa fa-font'></i></a>
						<a href='{{ url("/matkul/tapel/" . $mk -> id ."/nilai") }}' class='btn btn-xs btn-success' title='Nilai'><i class='fa fa-bar-chart'></i></a>
					</td>
				</tr>
				<?php $c++; ?>
				@endforeach
				@endif
			</tbody>
		</table>
	</div>
</div>
@endsection																			
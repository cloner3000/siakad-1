@extends('app')

@section('title')
Daftar Penugasan Dosen
@endsection

@section('scripts')
<script>
	$('.filter').change(function(){
		$('form#filter-form').submit();
	});
</script>
@endsection

@section('header')
<section class="content-header">
	<h1>
		Penugasan Dosen
		<small>Daftar</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Daftar Penugasan Dosen</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-warning">
	<div class="box-header with-border">
		<h3 class="box-title">Filter</h3>
	</div>
	<div class="box-body">
		<form method="get" action="{{ url('/dosen/penugasan/filter') }}" class="form-inline" id="filter-form">
			{!! csrf_field() !!}
			<div class="form-group">
				<label class="sr-only" for="dosen">Dosen</label>
				{!! Form::select('dosen', $dosen, Input::get('dosen'), ['class' => 'form-control filter']) !!}
			</div>
			<div class="form-group">
				<label class="sr-only" for="prodi">PRODI</label>
				{!! Form::select('prodi', $prodi, Input::get('prodi'), ['class' => 'form-control filter']) !!}
			</div>
			<div class="form-group">
				<label class="sr-only" for="ta">Tahun Ajaran</label>
				{!! Form::select('ta', $ta, Input::get('ta'), ['class' => 'form-control filter']) !!}
			</div>
			<div class="form-group">
				<button class="btn btn-warning btn-flat" type="submit"><i class="fa fa-filter"></i> Filter</button>
			</div>
		</form>
	</div>
</div>

<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Daftar Penugasan Dosen</h3>
		<div class="box-tools">
			<a href="{{ route('dosen.penugasan.export') }}" class="btn btn-success btn-xs btn-flat" title="Export Data Penugasan Dosen"><i class="fa fa-file-excel-o"></i> Export Penugasan Dosen</a>
			<a href="{{ route('dosen.penugasan.create') }}" class="btn btn-primary btn-xs btn-flat" title="Input Data Penugasan Dosen"><i class="fa fa-plus"></i> Tambah Penugasan Dosen</a>
		</div>
	</div>
	<div class="box-body">
		@if(!$penugasan->count())
		<p class="text-muted">Belum ada data</p>
		@else
		<?php $c=1; ?>
		<table class="table table-bordered table-striped">
			<thead>
				<tr>
					<th>No</th>
					<th>Nama</th>
					<th>NIDN / NUP / NIDK</th>
					<th>L/P</th>
					<th>Tahun Ajaran</th>
					<th>Program Studi</th>
					<th>No. Surat Tugas</th>
					<th>Tangal Surat Tugas</th>
					<th>Homebase?</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@foreach($penugasan as $g)
				<tr>
					<td>{{ $c }}</td>
					<td><a href="{{ route('dosen.penugasan', $g -> dosen_id) }}" title="Tampilkan data penugasan">{{ $g -> dosen }}</a></td>
					<td>{{ $g -> NIDN }}</td>
					<td>{{ $g -> jenisKelamin }}</td>
					<td>{{ $g -> tapel }}</td>
					<td>{{ $g -> strata }} {{ $g -> prodi }}</td>
					<td>{{ $g -> no_surat_tugas }}</td>
					<td>{{ $g -> tgl_surat_tugas }}</td>
					<td>{{ $g -> homebase }}</td>
					<td>
						<a href="{{ route('dosen.penugasan.delete', $g -> id) }}" class="btn btn-danger has-confirmation btn-flat btn-xs" title="Hapus data penugasan"><i class="fa fa-trash"></i> Hapus</a>
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
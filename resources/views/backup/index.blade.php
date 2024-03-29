@extends('app')

@section('title')
Backup Data
@endsection

@section('styles')
<style>
	ul{
	margin: 0px;
	padding-left: 17px;
	}
</style>
@endsection

@section('header')
<section class="content-header">
	<h1>
		Backup
		<small>Daftar Backup</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Daftar Backup</li>
	</ol>
</section>
@endsection

@section('content')
<div class="callout callout-danger">
	<h4>Peringatan! </h4>
	<ul>
		<li>Semua proses pada halaman ini berhubungan langsung dengan <strong>DATABASE {{ config('custom.app.abbr') }} {{ config('custom.app.version') }}</strong>. </li>
		<li>Pastikan anda mengetahui dengan pasti apa yang anda lakukan, sebelum melakukan operasi pada halaman ini. </li>
		<li>Kesalahan dalam melakukan operasi dapat mengakibatkan kerusakan Sistem. </li>
	</ul>
</div>
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Daftar Backup</h3>
		<div class="box-tools">
			<a href="{{ route('backup.create') }}" class="btn btn-info btn-xs btn-flat" title="Buat Backup"><i class="fa fa-plus"></i> Buat backup database</a>
			<a href="{{ route('backup.importform') }}" class="btn btn-danger btn-xs btn-flat" title="Import Backup"><i class="fa fa-cloud-download"></i> Impor file database</a>
		</div>
	</div>
	<div class="box-body">
		@if(!$backups -> count())
		<p class="text-muted">Belum ada data</p>
		@else
		<?php $c=1; ?>
		<table class="table table-bordered table-striped">
			<thead>
				<tr>
					<th>No</th>
					<th>Tanggal</th>
					<th>User</th>
					<th>File</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@foreach($backups as $g)
				<tr>
					<td>{{ $c }}</td>
					<td>{{ formatTanggalWaktu($g -> date) }}</td>
					<td>{{ $g -> executor -> authable -> nama }} </td>
					<td>{{ $g -> file }}</td>
					<td>
						<a href="{{ route('backup.export', $g -> id) }}?_token={{ csrf_token() }}" class="btn btn-success btn-xs btn-flat"><i class="fa fa-download"></i> Download file backup</a>
						<a href="{{ route('backup.restore', $g -> id) }}?_token={{ csrf_token() }}" class="btn btn-danger btn-xs btn-flat"><i class="fa fa-rotate-left"></i> Restore file backup</a>
						<a href="{{ route('backup.delete', $g -> id) }}?_token={{ csrf_token() }}" class="btn btn-warning btn-xs btn-flat has-confirmation" data-message="Hapus file backup?"><i class="fa fa-trash-o"></i> Delete</a>
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
@extends('app')

@section('title')
Daftar Semester
@endsection

@section('header')
<section class="content-header">
	<h1>
		Tahun Akademik
		<small>Daftar Tahun Akademik</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Daftar Tahun Akademik</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Daftar Tahun Akademik </h3>
		<div class="box-tools">
			<a href="{{ route('tapel.create') }}" class="btn btn-primary btn-xs btn-flat" title="Pendaftaran Tahun Akademik Baru"><i class="fa fa-plus"></i></a>
		</div>
	</div>
	<div class="box-body">
		@if(!$tapel->count())
		<p class="text-muted">Belum ada data</p>
		@else
		<?php $c = 1; ?>
		<table class="table table-bordered">
			<tr>
				<th>No.</th>
				<th>Tahun Akademik</th>
				<th>Waktu</th>
				<th>KRS</th>
				<th>Status</th>
				<th></th>
			</tr>
			@foreach($tapel as $smt)
			<tr>
				<td>{{ $c }}</td>
				<td>{{ $smt -> nama }}</td>
				<td>{{ formatTanggal($smt -> mulai) }} - {{ formatTanggal($smt -> selesai) }}</td>
				<td>{{ formatTanggal($smt -> mulaiKrs) }} - {{ formatTanggal($smt -> selesaiKrs) }}</td>
				<td>@if($smt -> aktif == 'y')<span style="color: #02bc63">Aktif</span>@else<span style="color: #999;">Tidak aktif</span>@endif</td>
				<td>
					<a href="{{ route('tapel.edit', $smt->id) }}" class="btn btn-warning btn-xs btn-flat"><i class="fa fa-pencil-square-o"></i> Edit</a>
				</td>
			</tr>
			<?php $c++; ?>
			@endforeach
		</table>
		@endif
	</div>
</div>
@endsection															
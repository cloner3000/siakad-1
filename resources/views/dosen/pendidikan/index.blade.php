@extends('app')

@section('title')
Daftar Pendidikan Dosen
@endsection

@section('header')
<section class="content-header">
	<h1>
		Pendidikan Dosen
		<small>Daftar</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Daftar Pendidikan Dosen</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Daftar Pendidikan Dosen</h3>
	</div>
	<div class="box-body">
		@if(!$pendidikan->count())
		<p class="text-muted">Belum ada data</p>
		@else
		<?php $c=1; ?>
		<table class="table table-bordered table-striped">
			<thead>
				<tr>
					<th>No</th>
					<th>Nama</th>
					<th>Bidang Studi</th>
					<th>Jenjang</th>
					<th>Gelar</th>
					<th>Perguruan Tinggi</th>
					<th>Fakultas</th>
					<th>Tahun Lulus</th>
					<th>SKS</th>
					<th>IPK</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@foreach($pendidikan as $b)
				<tr>
					<td>{{ $c }}</td>
					<td>{{ $b -> dosen }}</td>
					<td>{{ $b -> bidangStudi }}</td>
					<td>{{ $b -> jenjang }}</td>
					<td>{{ $b -> gelar }}</td>
					<td>{{ $b -> perguruanTinggi }}</td>
					<td>{{ $b -> fakultas }}</td>
					<td>{{ $b -> tahunLulus }}</td>
					<td>{{ $b -> sks }}</td>
					<td>{{ $b -> ipk }}</td>
					<td>
						<a href="{{ route('dosen.pendidikan.edit', [$b -> dosen_id, $b -> id]) }}" class="btn btn-warning btn-flat btn-xs" title="Edit data pendidikan"><i class="fa fa-pencil-square-o"></i> Edit</a>
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
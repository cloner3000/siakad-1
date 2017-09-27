@extends('app')

@section('title')
Komponen Penilaian
@endsection

@section('header')
<section class="content-header">
	<h1>
		Nilai
		<small>Komponen Penilaian</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/matkul/tapel/' . $matkul_tapel_id . '/nilai') }}"> Nilai</a></li>
		<li class="active">Komponen Penilaian</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Komponen Penilaian</h3>
		<div class="box-tools">
			<a href="{{ route('jenisnilai.create', $matkul_tapel_id) }}" class="btn btn-info btn-xs btn-flat"><i class="fa fa-plus"></i> Buat Komponen Penilaian baru</a>
		</div>
	</div>
	<div class="box-body">
		@if(count($types) < 1)
		<div class="alert alert-info">
			<strong>Info!</strong>  Belum ada data Komponen Penilaian
		</div>
		@else
		<?php $n = 1; ?>
		<div class="row">
			<div class="col-xs-6">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>No.</th>
							<th>Nama</th>
							<th>Bobot nilai (%)</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						@foreach($types as $type)
						<tr>
							<td>{{ $n }}</td>
							<td>{{ $type -> nama }}</td>
							<td>{{ $type -> bobot }}</td>
							<td>
								<a href="{{ route('jenisnilai.use', [$matkul_tapel_id, $type -> id]) }}" class="btn btn-primary btn-xs"><i class= "fa fa-check"></i> Pilih</a>
								<a href="{{ route('jenisnilai.edit', [$matkul_tapel_id, $type -> id]) }}" class="btn btn-warning btn-xs"><i class= "fa fa-edit"></i> Edit</a>
							</td>
						</tr>
						<?php $n++; ?>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
		@endif
	</div>
</div>
@endsection

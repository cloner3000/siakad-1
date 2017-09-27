@extends('app')

@section('title')
Periode PMB
@endsection

@section('header')
<section class="content-header">
	<h1>
		PMB
		<small>Periode PMB</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Periode PMB</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Periode PMB</h3>
		<div class="box-tools">
			<a href="{{ route('pmb.create') }}" class="btn btn-primary btn-xs btn-flat" title="Buka Periode PMB"><i class="fa fa-plus"></i> Buka Periode PMB</a>
		</div>
	</div>
	<div class="box-body">
		@if(!$pmb->count())
		<p class="text-muted">Belum ada data</p>
		@else
		<?php $c=1; ?>
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>No.</th>
					<th>Nama</th>
					<th>Tanggal</th>
					<th>Status</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@foreach($pmb as $g)
				<tr>
					<td>{{ $c }}</td>
					<td>{{ $g -> nama }}</td>
					<td>{{ formatTanggal($g -> mulai) }} - {{ formatTanggal($g -> selesai) }}</td>
					<td>@if($g -> buka == 'y')<i class="fa fa-unlock-alt text-success"></i>@else<i class="fa fa-lock text-danger"></i>@endif</td>
					<td>
						<a href="{{ route('pmb.peserta.index', $g->id) }}" class="btn btn-info btn-flat btn-xs" title="Pendaftar PMB"><i class="fa fa-share-alt"></i> Pendaftar</a>
						<!--a href="{{ route('pmb.ujian.index', $g->id) }}" class="btn btn-success btn-flat btn-xs" title="Tes"><i class="fa fa-filter"></i> Tes</a-->
						<a href="{{ route('pmb.edit', $g->id) }}" class="btn btn-warning btn-flat btn-xs" title="Edit data PMB"><i class="fa fa-pencil-square-o"></i> Edit</a>
						<a href="{{ route('pmb.delete', $g->id) }}" class="btn btn-danger btn-flat btn-xs has-confirmation" title="Hapus data PMB"><i class="fa fa-trash"></i> Hapus</a>
					</td>
				</tr>
				<?php $c++; ?>
				@endforeach
			</tbody>
		</table>
	</div>
</div>
@endif
@endsection												
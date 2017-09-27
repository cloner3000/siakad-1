@extends('app')

@section('title')
Riwayat Pembayaran
@endsection

@section('header')
<section class="content-header">
	<h1>
		Keuangan
		<small>Riwayat Pembayaran</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Riwayat Pembayaran</li>
	</ol>
</section>
@endsection

@section('content')
<div class="row">
	<div class="col-xs-12">
		<div class="box box-success">
			<div class="box-header with-border">
				<h3 class="box-title">Riwayat Pembayaran</h3>
			</div>
			<div class="box-body">	
				<?php $c = ($histories -> currentPage() - 1) * $histories -> perPage(); ?>
				<table class="table table-striped table-bordered">
					<thead>
						<tr>
							<th>No.</th>
							<th>Jenis Pembayaran</th>
							<th>Semester</th>
							<th>Tanggal</th>
							<th>Jumlah</th>
							<th>Petugas</th>
						</tr>
					</thead>
					<tbody>
						@if($histories -> count())
						@foreach($histories as $history)
						<?php $c++; ?>
						<tr>
							<td>{{ $c }}</td>
							<td>{{ $history -> jenis -> nama }}</td>
							<td>@if($history -> jenis_biaya_id == 2){{ $history -> semester }}@endif</td>
							<td>{{ formatTanggal(explode(' ', $history -> created_at)[0]) }}</td>
							<td>Rp {{ number_format($history -> jumlah, 0, ',', '.') }}</td>
							<td>{{ $history -> petugas -> authable -> nama }}</td>
						</tr>
						@endforeach
						@else
						<tr>
							<td colspan="6" align="center">Belum ada data</td>
						</tr>
						@endif
					</tbody>
				</table>
				
				{!! $histories -> render() !!}
			</div>
		</div>
	</div>
</div>
@endsection																																																																
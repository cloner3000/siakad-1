@extends('app')

@section('title')
Pendaftar PMB {{ $pmb -> nama }}
@endsection

@section('header')
<section class="content-header">
	<h1>
		PMB
		<small>{{ $pmb -> nama }}</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/pmb') }}">PMB</a></li>
		<li class="active">{{ $pmb -> nama }}</li>
	</ol>
</section>
@endsection

@push('styles')
<link href="{{ url('/lightbox2/css/lightbox.min.css') }}" rel="stylesheet">
@endpush

@push('scripts')
<script src="{{ url('/lightbox2/js/lightbox.min.js') }}"></script>
@endpush

@section('content')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Pendaftar PMB {{ $pmb -> nama }}</h3>
	</div>
	<div class="box-body">
		@if(!$peserta -> count())
		<p class="text-muted">Belum ada data</p>
		@else
		<?php $c=1; ?>
		<table class="table table-bordered">
			<thead>
				<tr>
					<th style="width: 30px;">No.</th>
					<th>Kode</th>
					<th>Print</th>
					<th>Slip</th>
					<th>Nama</th>
					<th>Alamat</th>
					<th>Telp</th>
					<th>Tanggal</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@foreach($peserta as $g)
				<tr>
					<td>{{ $c }}</td>
					<td>{{ $g -> kode }}</td>
					<td>
						<a href="{{ route('pmb.peserta.print', ['formulir', $g -> kode]) }}" title="Formulir" target="_blank">Formulir</a>
						<a href="{{ route('pmb.peserta.print', ['kartu', $g -> kode]) }}" title="Kartu" target="_blank">Kartu</a>
					</td>
					<td>
						@if($g -> slip != '')
						<a href="{{ url('/getimage/' . $g -> slip) }}" class="btn btn-success btn-xs btn-flat" data-lightbox="slip-{{ $g -> id }}" data-title="Slip pembayaran {{ $g -> nama }}"><i class="fa fa-image"></i></a>
						@endif
					</td>
					<td>{{ $g -> nama }}</td>
					<td>{{ $g -> alamatMhs }}</td>
					<td>{{ $g -> telpMhs }}</td>
					<td>{{ formatTanggalWaktu($g -> created_at) }}</td>
					<td>
						<a href="{{ route('pmb.peserta.delete', [$pmb -> id, $g -> kode]) }}" class="btn btn-danger btn-xs btn-flat has-confirmation"><i class="fa fa-trash"></i></a>
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
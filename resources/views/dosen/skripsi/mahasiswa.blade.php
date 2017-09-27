@extends('app')

@section('title')
Mahasiswa Bimbingan Dosen  | {{ $dosen -> nama }}
@endsection

@section('header')
<section class="content-header">
	<h1>
		Dosen
		<small>Mahasiswa Bimbingan</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/dosen/') }}"> Dosen</a></li>
		<li><a href="{{ url('/dosen/' . $dosen -> id) }}"> {{ $dosen -> nama }}</a></li>
		<li class="active">Mahasiswa Bimbingan</li>
	</ol>
</section>
@endsection

@push('scripts')
<script src="{{ asset('/js/chosen.jquery.min.js') }}"></script>
<script>
	$(function(){
		$(".chosen-select").chosen({
			no_results_text: "Tidak ditemukan hasil pencarian untuk: ",
			placeholder_text_single: "Pilih program studi terlebih dahulu"
		});
	});  
</script>
@endpush
@push('styles')
<link rel="stylesheet" href="{{ asset('/css/chosen.min.css') }}">
<style>
	.chosen-container{
	font-size: inherit;
	}
	.chosen-single{
	padding: 6px 10px !important;
	box-shadow: none !important;
    border-color: #d2d6de !important;
	background: white !important;
	height: 34px !important;
	border-radius: 0px !important;
	}
	.chosen-drop{
    border-color: #d2d6de !important;	
	box-shadow: none;
	}
</style>
@endpush

@section('content')
<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Dosen</h3>
		<div class="box-tool pull-right">
			@include('dosen.partials._menu', ['dosen' => $dosen])
		</div>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-xs-12">
				<table width="100%">
					<tbody>
						<tr>
							<th width="20%">Nama</th><th width="2%">:</th><td width="30%">{{ $dosen -> nama }}</td>
							<th width="20%"></th><th width="2%"></th><td></td>
						</tr>
						<tr>
							<th>Tempat Lahir</th><th>:</th><td>{{ $dosen -> tmpLahir }}</td>
							<th>Tanggal Lahir</th><th>:</th><td>{{ $dosen -> tglLahir }}</td>
						</tr>
						<tr>
							<th>Jenis Kelamin</th><th>:</th><td>@if($dosen -> jenisKelamin == 'L') Laki-laki @else Perempuan @endif</td>
							<th>Agama</th><th>:</th><td>{{ config('custom.pilihan.agama')[$dosen -> agama] }}</td>
						</tr>
						<tr>
							<th>Status</th><th>:</th><td>{{ config('custom.pilihan.statusKepegawaian')[$dosen -> statusKepegawaian] }}</td>
							<th>NIDN</th><th>:</th><td>{{ $dosen -> NIDN }}</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>		
	</div>
</div>
<div class="box box-danger">
	<div class="box-header with-border">
		<h3 class="box-title">Mahasiswa Bimbingan</h3>
	</div>
	<div class="box-body">
		<?php $c = 1; ?>
		<table class="table table-bordered table-hover">
		<thead>
			<tr>
				<th>No</th>
				<th>NIM</th>
				<th>Mahasiswa</th>
				<th>Prodi</th>
				<th>Program</th>
				<th>Judul</th>
				<th>Tanggal Awal Bimbingan</th>
				<th>Tanggal Akhir Bimbingan</th>
				<th></th>
			</tr>
			</thead>
			<tbody>
			@if(!count($data))
			<td colspan="8" align="center">Belum ada data</td>
			@else
			@foreach($data as $mk)
			<tr>
			<td>{{ $c }}</td>
			<td>{{ $mk -> NIM }}</td>
			<td>{{ $mk -> mhs }}</td>
			<td>{{ $mk -> strata }} {{ $mk -> prodi }}</td>
			<td>{{ $mk -> program }}</td>
			<td>{{ $mk -> judul }}</td>
			<td>{{ $mk -> tglAwal }}</td>
			<td>{{ $mk -> tglAkhir }}</td>
			<td>
			<a href="{{ route('dosen.skripsi.mahasiswa.delete', [$dosen -> id, $mk -> skripsi_id]) }}" title="Hapus data Mahasiswa" class="btn btn-danger btn-xs btn-flat has-confirmation"><i class="fa fa-trash"></i></a>
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
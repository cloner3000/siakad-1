@extends('app')

@section('title')
Tawaran Mata Kuliah
@endsection

@section('styles')
<style>
	.checkbox{
	margin: 0px !important;
	padding-top: 0px !important;
	}
</style>
@endsection

@section('header')
<section class="content-header">
	<h1>
		Mahasiswa
		<small>Tawaran Mata Kuliah</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Tawaran Mata Kuliah</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Tawaran Mata Kuliah</h3>
	</div>
	<div class="box-body">
		@if(!count($matkul))
		@if(!$open)
		<div class="callout callout-danger">
			<h4>Error</h4>
			Waktu pengisian KRS Online sudah habis. Hubungi bagian Akademik jika anda belum melakukan KRS Online.
		</div>
		@else
		<p class="text-muted">Tidak ada Mata Kuliah yang ditawarkan pada semester ini</p>
		@endif
		@else
		<?php $c=1; ?>
		{!! Form::model(new Siakad\Krs, ['class' => 'form-horizontal', 'role' => 'form', 'route' => ['krs.store']]) !!}
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>No</th>
					<th>Mata Kuliah</th>
					<th>Dosen</th>
					<th>Semester</th>
					<th>SKS</th>
					<!--th>Jadwal</th-->
					<th>PRODI</th>
					<th>Kelas</th>
					<th>Kapasitas</th>
				</tr>
			</thead>
			<tbody>
				@foreach($matkul as $g)
				<?php
					$full = false;
					if(intval($g -> kuota) === 0 )
					{
						$full = true;
					}
					elseif(intval($g -> peserta) / intval($g -> kuota) === 1) 
					{
						$full = true;
					}
				?>
				<tr>
					<td>{{ $c }}</td>
					<td>
						@if($full or !$open)
						{{ $g -> nama_matkul }} ({{ $g -> kode }})
						@else
						<div class="checkbox"><label>{!! Form::checkbox('matkul_tapel_id[]', $g -> mtid) !!} {{ $g -> nama_matkul }} ({{ $g -> kode }})</label></div>
						@endif
					</td>
					<td>{{ $g -> dosen }}</td>
					<td>{{ $g -> semester }}</td>
					<td>{{ $g -> sks }}</td>
					<!--td>@if(isset($g -> hari))<strong>{{ config('custom.hari')[$g -> hari] }}</strong>, {{ $g -> jam_mulai }} - {{ $g -> jam_selesai }} ({{ $g -> nama_ruang }})@else<span class="text-muted">-</span>@endif</td-->
					<td>{{ $g -> nama_prodi }} {{ $g -> program }}</td>
					<td>{{ $g -> kelas2 }}</td>
					<td>@if($full)<span class="text-danger">Penuh</span>@else{{ $g -> peserta }} / {{ $g -> kuota }}@endif</td>
				</tr>
				<?php $c++; ?>
				@endforeach
			</tbody>
		</table>
		@if($open)
		{!! Form::hidden('mahasiswa_id', $mhs -> id) !!}
		{!! Form::hidden('krs_id', $krs -> id) !!}
		<div class="form-group">
			<div class="col-sm-10">
				<button class="btn btn-primary btn-flat" type="submit"><i class="fa fa-plus"></i> Tambahkan</button>
			</div>		
		</div>
		@endif
		{!! Form::close() !!}
		@endif
	</div>
</div>
@endsection																									
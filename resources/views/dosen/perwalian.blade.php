@extends('app')

@section('title')
Dosen Wali
@endsection

@section('header')
<section class="content-header">
	<h1>
		Dosen
		<small>Perwalian</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Perwalian</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Data Mahasiswa Wali</h3>
	</div>
	<div class="box-body">
		<table class="table table-bordered table-striped">
			<thead>
				<tr>
					<th>No.</th>
					<th>NIM</th>
					<th>Nama</th>
					<th>L/P</th>
					<th>PRODI</th>
					<th>Program</th>
					<th>Sem.</th>
					<th>SKS</th>
					<!--
						<th>Isi</th>
						<th>Approve</th>
						<th>SPP</th>
					-->
					<th>Status</th>
					<th>Link</th>
				</tr>
			</thead>
			<tbody>
				@if(!$mahasiswa -> count())
				<tr><td colspan="12">Data Mahasiswa tidak ditemukan</td></tr>
				@else
				<?php 
					$per_page = $mahasiswa -> perPage();
					$total = $mahasiswa -> total();
					$c = ($mahasiswa -> currentPage() - 1) * $per_page;
					$last = $c + $per_page > $total ? $total : $c + $per_page;
				?>
				@foreach($mahasiswa as $mhs)
				<?php $c++; ?>
				<tr>
					<td>{{ $c }}</td>
					<td>{{ $mhs -> NIM }}</td>
					<td>{{ $mhs -> nama }}</td>
					<td>{{ $mhs -> jenisKelamin }}</td>
					<td>{{ $mhs -> strata }} {{ $mhs -> prodi }}</td>
					<td>{{ $mhs -> kelas }}</td>
					<td>{{ $mhs -> semesterMhs }}</td>
					<td>{{ $mhs -> sks }}</td>
					<!--
						<td></td>
						<td></td>
						<td></td>
					-->
					<td>{{ config('custom.pilihan.statusMhs')[$mhs -> statusMhs] }}</td>
					<td>
						<div class="btn-group">
							<a class="btn btn-warning btn-xs btn-flat" href='{{ url("/mahasiswa/". $mhs -> NIM ."/krs") }}' title='Tampilkan KRS'><i class="fa fa-puzzle-piece"></i></a>
							<a class="btn btn-info btn-xs btn-flat" href='{{ url("/mahasiswa/". $mhs -> NIM ."/khs") }}' title='Tampilkan KHS'><i class="fa fa-list-alt"></i></a>
							<a class="btn btn-success btn-xs btn-flat" href='{{ url("/mahasiswa/". $mhs -> id ."/transkrip") }}' title='Transkrip'><i class="fa fa-file-text-o"></i></a>
							<a class="btn btn-primary btn-xs btn-flat" href='{{ url("/mahasiswa/". $mhs -> NIM ."/kemajuan") }}' title='Kemajuan Belajar'><i class="fa fa-line-chart"></i></a>
						</div>
					</td>
				</tr>
				@endforeach
				@endif
			</tbody>
		</table>
		{!! $mahasiswa -> render() !!}
	</div>
@endsection																						
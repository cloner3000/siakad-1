@extends('app')

@section('title')
Jadwal Perkuliahan
@endsection

@section('header')
<section class="content-header">
	<h1>
		Perkuliahan
		<small>Jadwal Perkuliahan</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Jadwal Perkuliahan</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Jadwal Perkuliahan</h3>
	</div>
	<div class="box-body">
		<?php 
			$c = 1; 
			$today = date('N');
		?>
		<table class="table table-bordered table-hover">
			<thead>
				<tr>
					<th>No.</th>
					<th>Jadwal</th>
					<th>Mata Kuliah</th>
					<th>Dosen</th>
					<th>Semester</th>
					<th>Program</th>
					<th>Kelas</th>
					<th>Ruang</th>
					<th class="hidden-print">RPP</th>
					<th class="hidden-print">Silabus</th>
				</tr>
			</thead>
			<tbody>
				@if(!$data -> count())
				<td colspan="9" align="center">Belum ada data</td>
				@else
				@foreach($data as $mk)
				<tr @if($mk -> hari == $today)class="info" @endif >
					<td>{{ $c }}</td>
					<td>@if(isset($mk -> hari)){{ config('custom.hari')[$mk -> hari] }}, {{ $mk -> jam_mulai }} - {{ $mk -> jam_selesai }}@else<span>-</span>@endif</td>
					<td>{{ $mk -> matkul }} ({{ $mk -> kd }})</td>
					<td>{{ $mk -> dosen }}</td>
					<td>{{ $mk -> semester }}</td>
					<td>{{ $mk -> program }}</td>
					<td>{{ $mk -> kelas }}</td>
					<td>{{ $mk -> ruang or '-' }}</td>
					<td class="hidden-print">@if(isset( $mk -> rpp))<a href="{{ url('/download/' . $mk -> rpp . '/' . csrf_token()) }}" class="btn btn-primary btn-xs" title="Download"><i class="fa fa-download"></i></a>@else<a href="" class="btn btn-default btn-xs" disabled="disabled" title="Download"><i class="fa fa-download"></i></a>@endif</td>
					<td class="hidden-print">@if(isset( $mk -> silabus))<a href="{{ url('/download/' . $mk -> silabus . '/' . csrf_token()) }}" class="btn btn-primary btn-xs" title="Download"><i class="fa fa-download"></i></a>@else<a href="" class="btn btn-default btn-xs" disabled="disabled" title="Download"><i class="fa fa-download"></i></a>@endif</td>
				</tr>
				<?php $c++; ?>
				@endforeach
				@endif
			</tbody>
		</table>
	</div>
</div>
@endsection																			
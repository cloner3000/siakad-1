@extends('app')

@section('title')
Jadwal Kuliah
@endsection

@section('scripts')
<script>
	$('.filter').change(function(){
		$('form#filter-form').submit();
	});
	$('.table tr.danger').on({
		mouseenter: function () {
			$('tr#' + $(this).attr('id')).addClass('blink');
		},
		mouseleave: function () {
			$('tr#' + $(this).attr('id')).removeClass('blink');
		}
	});
</script>
@endsection

@section('styles')
<style>
	.blink {
	background-color: red;
	animation: blinker 1s linear infinite;
	}
	@keyframes blinker {  
	50% { opacity: 0; }
	}
</style>
@endsection

@section('content')
@if(isset($prodi))
<div class="box box-warning">
	<div class="box-header with-border">
		<h3 class="box-title">Filter</h3>
		</div>
		<div class="box-body">
			<form method="get" action="{{ url('/jadwal2') }}" class="form-inline" id="filter-form">
				{!! csrf_field() !!}
				<div class="form-group">
					<label class="sr-only" for="prodi">PRODI</label>
					{!! Form::select('prodi', $prodi, Input::get('prodi'), ['class' => 'form-control filter']) !!}
				</div>
				<div class="form-group">
					<label class="sr-only" for="ta">Tahun Akademik</label>
				{!! Form::select('ta', $ta, Input::get('ta'), ['class' => 'form-control filter']) !!}
				</div>
				<div class="form-group">
					<button class="btn btn-warning btn-flat" type="submit"><i class="fa fa-filter"></i> Filter</button>
				</div>
		</form>
	</div>
</div>
@endif
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Kelas Kuliah </h3>
		<div class="box-tools">
			<a href="{{ url('/jadwal') }}" class='btn btn-success btn-xs btn-flat' title='Tabel'><i class='fa fa-table'></i></a>
			<a href="{{ url('/jadwal/create') }}" class='btn btn-primary btn-xs btn-flat' title='Buat Jadwal'><i class='fa fa-plus'></i> Jadwal Baru</a>
		</div>
	</div>
	<div class="box-body">
		@if(count($data) < 1)
		<div class="alert alert-info alert-dismissible">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<h4><i class="icon fa fa-info"></i> Informasi</h4>
			Data jadwal tidak ditemukan
		</div>
		@else
		<?php $c = 1; ?>
		<table class="table table-bordered table-hover">
			<thead>
				<tr>
					<th>No.</th>
					<th>Hari</th>
					<th>Jam</th>
					<th>Mata Kuliah</th>
					<th>Dosen</th>
					<th>Semester</th>
					<th>SKS</th>
					<th>Prodi</th>
					<th>Program</th>
					<th>Kelas</th>
					<th>Ruang</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@if(!$data -> count())
				<td colspan="7" align="center">Belum ada data</td>
				@else
				<?php $crash = ''; $j = []; ?>
				@foreach($data as $mk)
				<?php
					$d = str_slug($mk -> hari . $mk -> jam_mulai . $mk -> dosen);
					if(in_array($d, $j)) $crash = 'danger';
					$j[] = $d;
				?>
				<tr class="{{ $crash }}" id="{{ str_slug($mk -> hari . $mk -> jam_mulai . $mk -> dosen_id) }}">
					<td>{{ $c }}</td>
					<td>
						@if($mk -> hari != '' )
						{{ config('custom.hari')[$mk -> hari] }}
						@else
						-
						@endif
					</td>
					<td>
						@if($mk -> hari != '' )
						{{ $mk -> jam_mulai }} - {{ $mk -> jam_selesai }}
						@else
						-
						@endif
					</td>
					<td>{{ $mk -> matkul }} ({{ $mk -> kd }}) Kelas {{ $mk -> kelas }}</td>
					<td>{{ $mk -> dosen }}</td>
					<td>{{ $mk -> semester }}</td>
					<td>{{ $mk -> sks }}</td>
					<td>{{ $mk -> prodi }}</td>
					<td>{{ $mk -> program }}</td>
					<td>{{ $mk -> kelas2 }}</td>
					<td>{{ $mk -> ruang }}</td>
					<td>
						@if($mk -> jid)
						<a href="{{ route('matkul.tapel.jadwal.edit', $mk -> jid) }}" class="btn btn-xs btn-success"><i class="fa fa-pencil"></i></a>
						<a href="{{ route('matkul.tapel.jadwal.delete', $mk -> jid) }}" class="btn btn-xs btn-danger has-confirmation"><i class="fa fa-trash"></i></a>
						@else
						<a href="{{ url('/jadwal/create?id=' . $mk -> mtid) }}" class="btn btn-xs btn-info" title="Baru"><i class="fa fa-plus-square-o"></i></a>
						@endif
					</td>
				</tr>
				<?php $c++; ?>
				<?php $crash = ''; $d = ''; ?>
				@endforeach
				@endif
			</tbody>
			</table>
			@endif
			</div>
			</div>
			@endsection																																																																
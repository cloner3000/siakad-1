@extends('app')

@section('title')
Form Pembayaran
@endsection

@section('header')
<section class="content-header">
	<h1>
		Keuangan
		<small>Form Pembayaran  @if($mahasiswa !== null)<a href="{{ url('/biaya/form?nim=' . $mahasiswa -> NIM . '&update=true') }}" class="btn btn-primary btn-xs btn-flat"><i class="fa fa-refresh" title="Reload"></i></a>@endif</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Form Pembayaran</li>
	</ol>
</section>
@endsection

@push('scripts')
<script src="{{ asset('/js/jquery.inputmask.bundle.min.js') }}"></script>
<script>
	$(function(){
		$(".currency").inputmask('999.999.999', { numericInput: true, autoUnmask: true, removeMaskOnSubmit: true, unmaskAsNumber: true });
	});
	$(document).on('change', 'select[name=jenis_biaya_id]', function(){
		if($(this).val() == 2)
		{
			$('.semester_div').removeClass('hidden');
		}
		else
		{
			$('.semester_div').addClass('hidden');
		}
	});
	/* 
		//seharuse sisa pembayaran
		var biaya=new Array();
		@if(count($biaya))
		@foreach($biaya as $k => $v)
		biaya[{{ $k }}] = {{ $v }};
		@endforeach
		@endif
		$(document).on('click', '.btn-lunas', function()
		{
		$('input[name=jumlah]').val(biaya[$('select[name=jenis_biaya_id]').val()]);
		}); 
	*/
</script>
@endpush

@section('content')
<div class="row">
	<div class=@if($mahasiswa !== null)"col-md-6"@else"col-sm-12"@endif>
		<div class="box box-info">
			<div class="box-header with-border">
				<h3 class="box-title">Data Mahasiswa</h3>
			</div>
			<div class="box-body">
				<form role="form">
					<div class="form-group has-feedback{{ ($mahasiswa == null and $nim != '') ? ' has-error' : '' }}">
						<label for="nim">NIM Mahasiswa</label>
						<div class="input-group">
							<input type="text" class="form-control" id="nim" name="nim" value="{{ $nim or '' }}" placeholder="NIM Mahasiswa" autofocus="autofocus">
							<span class="input-group-btn">
								<button type="submit" class="btn btn-info btn-flat">Cari</button>
							</span>
						</div>
						@if ($mahasiswa == null and $nim != '')
						<span class="help-block">
							<strong>NIM tidak ditemukan</strong>
						</span>
						@endif
					</div>
					@if($mahasiswa !== null)
					<div class="form-group">
						<label for="nama">Nama</label>
						<p class="form-control-static">@if($mahasiswa !== null) {{ $mahasiswa -> nama }} ({{ $mahasiswa -> NIM }})@endif</p>
					</div>
					<div class="form-group">
						<label for="prodi">PRODI / Program / Semester / Status Tinggal</label>
						<p class="form-control-static">@if($mahasiswa !== null) {{ $mahasiswa -> prodi -> nama }} / {{ $mahasiswa -> kelas -> nama }} / {{ $mahasiswa -> semesterMhs }} / {{ config('custom.pilihan.jenisPembayaran')[$mahasiswa -> jenisPembayaran] }} @endif</p>
					</div>
					@endif
				</form>
			</div>
		</div>
	</div>
	@if($mahasiswa !== null)
	<div class="col-md-6">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">Form Transaksi</h3>
			</div>
			<div class="box-body">
				@if(!count($jenis_list))
				<p class="help-block"><a href="{{ url('/biaya/setup/' . substr($mahasiswa -> NIM, 0, 4) . '/' . $mahasiswa -> prodi -> id . '/' . $mahasiswa -> kelas -> id . '/' . $mahasiswa -> jenisPembayaran) }}"> Biaya Kuliah</a> belum diatur.</p>
				@else
				<form role="form" method="POST" action="{{ url('/biaya/form') }}">
					{{ csrf_field() }}
					<div class="form-group">
						<label for="jenis_id">Jenis Pembayaran</label>
						{!! Form::select('jenis_biaya_id', $jenis_list, null, ['class' => 'form-control']) !!}
					</div>
					<div class="form-group semester_div hidden">
						<label for="semester">Semester</label>
						{!! Form::select('semester', $semesters, $mahasiswa -> semesterMhs, ['class' => 'form-control']) !!}
					</div>
					<div class="form-group has-feedback{{ $errors->has('jumlah') ? ' has-error' : '' }}">
						<label for="jumlah">Jumlah Pembayaran</label>
						<div class="input-group">
						<span class="input-group-addon">Rp </span>
						{!! Form::hidden('nim', $mahasiswa -> NIM) !!}
						{!! Form::hidden('mahasiswa_id', $mahasiswa -> id) !!}
						{!! Form::hidden('prodi_id', $mahasiswa -> prodi -> id) !!}
						{!! Form::hidden('program_id', $mahasiswa -> kelas -> id) !!}
						{!! Form::hidden('jenisPembayaran', $mahasiswa -> jenisPembayaran) !!}
						{!! Form::text('jumlah', '', ['class' => 'form-control currency']) !!}
						<!--span class="input-group-btn">
							<button class="btn btn-success btn-flat btn-lunas" type="button">Lunasi</button>
						</span-->
						</div>
						@if ($errors->has('jumlah'))
						<span class="help-block">
							<strong>{{ $errors->first('jumlah') }}</strong>
						</span>
						@endif
					</div>
					<button class="btn btn-flat btn-primary" type="submit"><i class="fa fa-floppy-o"></i> Simpan</button>
				</form>
				@endif		
			</div>
		</div>		
	</div>
	@endif
</div>
@if($status != null)
<div class="row">
	<div class="col-xs-12">
		<div class="box box-danger">
			<div class="box-header with-border">
				<h3 class="box-title">Status Pembayaran</h3>
				<div class="pull-right box-tools">
					<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
				</div>
			</div>
			<div class="box-body no-padding">
				<?php 
					$c = 1; 
					$tanggungan = $dibayar = $sisa = 0;
				?>
				<table class="table table-striped">
					<thead>
						<tr>
							<th>No.</th>
							<th>Jenis Pembayaran</th>
							<th>Harus dibayar</th>
							<th>Sudah dibayar</th>
							<th>Sisa</th>
							<th>Prosentase</th>
							<th>Keterangan</th>
						</tr>
					</thead>
					<tbody>
						@foreach($status as $s)
						<?php 
							$prosentase = (int)$s -> tanggungan == 0 ? 0 : (int)$s -> dibayar / (int)$s -> tanggungan * 100; 
						?>
						<tr>
							<td>{{ $c }}</td>
							<td>{{ $s -> nama }}</td>
							<td>Rp {{ number_format($s -> tanggungan, 0, ',', '.') }}</td>
							<td>Rp {{ number_format($s -> dibayar, 0, ',', '.') }}</td>
							<td>Rp {{ number_format($s -> sisa, 0, ',', '.') }}</td>
							<td>{{ round($prosentase, 1) }}%</td>
							<td>@if($s -> sisa > 0)Tunggakan Rp {{ number_format($s -> sisa, 0, ',', '.') }}@else<span>Lunas</span>@endif</td>
						</tr>
						<?php 
							$c++; 
							$tanggungan += $s -> tanggungan;
							$dibayar += $s -> dibayar;
							$sisa += $s -> sisa;
						?>
						@endforeach
						<tr>
							<th colspan="2" style="text-align:right">Total</th>
							<th>Rp {{ number_format($tanggungan, 0, ',', '.') }}</th>
							<th>Rp {{ number_format($dibayar, 0, ',', '.') }}</th>
							<th colspan="3">Rp {{ number_format($sisa, 0, ',', '.') }}</th>
						</tr>
					</tbody>
					</table>
				<br/>
				<a href="{{ url('/biaya/' . $mahasiswa -> NIM . '/cetak/status') }}" target="_blank" class="btn btn-danger btn-flat" title="Cetak Status Pembayaran" style="margin: 0 0 10px 10px;"><i class="fa fa-print"></i> Cetak</a>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-xs-12">
		<div class="box box-success">
			<div class="box-header with-border">
				<h3 class="box-title">Riwayat Transaksi <small>(10 Transaksi terakhir)</small></h3>
			</div>
			<div class="box-body no-padding">	
				<?php $c = 1; ?>
				<table class="table table-striped">
					<thead>
						<tr>
							<th>No.</th>
							<th>Jenis Pembayaran</th>
							<th>Semester</th>
							<th>Tanggal</th>
							<th>Jumlah</th>
							<th>Petugas</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						@if($histories -> count())
						@foreach($histories as $history)
						<tr>
							<td>{{ $c }}</td>
							<td>{{ $history -> jenis -> nama }}</td>
							<td>@if($history -> jenis_biaya_id == 2){{ $history -> semester }}@endif</td>
							<td>{{ formatTanggal(explode(' ', $history -> created_at)[0]) }}</td>
							<td>Rp {{ number_format($history -> jumlah, 0, ',', '.') }}</td>
							<td>{{ $history -> petugas -> authable -> nama }}</td>
							<td>
								<a href="{{ url('/biaya/'. $history -> id .'/cetak/kwitansi') }}" class="btn btn-success btn-xs btn-flat" title="Cetak Kwitansi Pembayaran" target="_blank"><i class="fa fa-print"></i></a>
								<a href="{{ url('/biaya/'. $mahasiswa -> NIM .'/'. $history -> id .'/delete') }}" class="btn btn-danger btn-xs btn-flat has-confirmation" title="Hapus Data Pembayaran"><i class="fa fa-trash"></i></a>
							</td>
						</tr>
						<?php $c++; ?>
						@endforeach
						@else
						<tr>
							<td colspan="6" align="center">Belum ada data</td>
						</tr>
						@endif
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
@endif	
@endsection																																																																
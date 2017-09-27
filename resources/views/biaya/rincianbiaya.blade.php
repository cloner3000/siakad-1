@extends('app')

@section('title')
Rincian Biaya Pendidikan
@endsection

@section('header')
<section class="content-header">
	<h1>
		Keuangan
		<small>Rincian Biaya Pendidikan</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Rincian Biaya Pendidikan</li>
	</ol>
</section>
@endsection

@push('scripts')
<script>
	$(document).on('click', '.btn-filter', function(){
		window.location.href="{{ url('/biaya/detail') }}/" + $('select[name=angkatan]') .val() + "/" + $('select[name=prodi_id]') .val() + "/" + $('select[name=program_id]') .val() + "/" + $('input[name=jenisPembayaran]:checked') .val();
	});
	
	$('[data-toggle="tooltip"]').tooltip({'placement': 'auto top'});
</script>
@endpush

@section('content')
<div class="row">
	<div class="col-md-4 col-xs-6">
		<div class="box box-warning">
			<div class="box-header with-border">
				<h3 class="box-title">Filter</h3>
			</div>
			<div class="box-body">
				<div role="form">
					<div class="form-group">
						<label for="angkatan">Angkatan</label>
						{!! Form::select('angkatan', $angkatan, $data['angkatan'], ['class' => 'form-control']) !!}
					</div>
					<div class="form-group">
						<label for="prodi_id">PRODI</label>
						{!! Form::select('prodi_id', $prodi, $data['prodi_id'], ['class' => 'form-control']) !!}
					</div>
					<div class="form-group">
						<label for="program_id">Program</label>
						{!! Form::select('program_id', $program, $data['program_id'], ['class' => 'form-control']) !!}
					</div>						
					<div class="form-group">
						<label for="jenisPembayaran">Jenis</label>
						<div>
							<?php
								foreach(config('custom.pilihan.jenisPembayaran') as $k => $v) 
								{
									echo '<label class="radio-inline">';
									echo '<input type="radio" name="jenisPembayaran" ';
									if($k == $data['jenisPembayaran']) echo 'checked="checked" ';
									echo 'value="'. $k .'"> '. $v .'</label>';
								}
							?>
						</div>
					</div>
					<button class="btn btn-warning btn-flat btn-filter"><i class="fa fa-filter"></i> Filter</button>
				</div>
			</div>
		</div>
	</div>	
	
	<div class="col-md-8 col-xs-6">
		<div class="box box-danger">
			<div class="box-header with-border">
				<h3 class="box-title">Biaya Pendidikan</h3>
			</div>
			<div class="box-body">
				@if(!isset($jenis2))
				<p class="text-muted">Data tidak ditemukan</p>
				@else
				<table class="table table-bordered table-striped">
					<tr>
						<?php $x = 1; $total = 0;?>
						@foreach($jenis2 as $j)
						<th>{{ $x }}</th>
						<th>{{ $j['nama'] }}</th>
						<td style="width: 20px; border-right-color: transparent;">Rp</td>
						<td class="align-right">{{ number_format($j['tanggungan'] , 0, ',', '.') }}</td>
						<?php 
							$x++; 
							$total += $j['tanggungan'];
						?>
						@if($x%2 != 0)</tr><tr>@endif
						@endforeach
					</tr>
				</table>
				<h3 style="text-align: right;">Total: Rp{{ number_format($total , 0, ',', '.') }}</h3>
				@endif
			</div>
		</div>		
	</div>
	
</div>
<style>
	table.rincian{
	font-size: 13px;
	border-collapse: collapse;
	width: 100%;
	}
	.rincian th{
	text-align: center;
	padding: 0px 2px;
	}
	.rincian th, .rincian td{
	color: #000;
	border: 1px solid black;
	}
	.rincian td{
	text-align: right;
	font-size: 11px;
	}
	.align-right{
	text-align: right !important;
	}
	.align-left{
	text-align: left !important;
	}
	.align-center{
	text-align: center !important;
	}
	/* 	.tooltip-arrow{
	border-top-color: #00c0ef !important;
	}
	.tooltip-inner{
	background-color: #00c0ef;
	} */
</style>
<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Rincian Biaya Pendidikan Mahasiswa {{ $title }}</h3>
	</div>
	<div class="box-body">
		@if(count($rincian) < 1)
		<p class="text-muted">Data tidak ditemukan</p>
		@else
		<?php $c = 1; ?>
		<table class="rincian">
			<thead>
				<tr>
					<th width="20px">No</th>
					<th>Nama</th>
					<?php $n = 1; ?>
					@foreach($jenis2 as $k => $v)
					<th><div data-toggle="tooltip" title="{{ $v['nama'] }}">{{ $n}}</div></th>
					<?php $n++; ?>
					@endforeach
				</tr>
			</thead>
			<tbody>
				<!--tr>
					<th colspan="2" class="align-center">Tanggungan</th>
					@foreach($jenis2 as $k => $v)
					<td>{{ number_format($v['tanggungan'] , 0, ',', '.') }}</td>
					@endforeach
				</tr-->
				@foreach($rincian as $k => $v)
				<?php
					$id = explode('-', $k);
				?>
				<tr>
					<td class="align-left">{{ $c }}</td>
					<td class="align-left">{{ $id[1] }}</td>
					@foreach($v as $r)
					<td>@if($r -> dibayar > 0){{ number_format($r -> dibayar , 0, ',', '.') }}@endif</td>
					@endforeach
				</tr>
				<?php $c++; ?>
				@endforeach		
			</tbody>
		</table>
		<br/>
		<p>
			<a class="btn btn-primary btn-flat" href="{{ route('biaya.detail.print', $data) }}"><i class="fa fa-print"></i> Cetak</a>&nbsp;
			<a class="btn btn-success btn-flat" href="{{ route('biaya.detail.save', $data) }}?format=xlsx"><i class="fa fa-file-excel-o"></i> Excel</a>&nbsp;
			<!--a class="btn btn-danger btn-flat" href="{{ route('biaya.detail.save', $data) }}?format=pdf"><i class="fa fa-file-pdf-o"></i> PDF</a-->
		</p>
		@endif
	</div>
</div>
@endsection																																																																											
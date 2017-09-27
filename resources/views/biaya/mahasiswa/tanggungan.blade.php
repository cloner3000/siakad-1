@extends('app')

@section('title')
Status Pembayaran
@endsection

@section('header')
<section class="content-header">
	<h1>
		Keuangan
		<small>Status Pembayaran</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Status Pembayaran</li>
	</ol>
</section>
@endsection

@push('scripts')
@if(isset($status))
<script>
	$(document).ready(function()
	{
		var tg = $('.tanggungan').detach();
		tg.insertAfter('.status');
	});
</script>
@endif
@endpush

@section('content')
<div class="row tanggungan">
	<div class="col-xs-12">
		<div class="box box-danger">
			<div class="box-header with-border">
				<h3 class="box-title">Tanggungan Keuangan</h3>
			</div>
			<div class="box-body">
				<?php 
					$c = 1; 
					$tanggungan = $dibayar = $sisa = $tp = 0;
				?>
				<table class="table table-striped table-bordered">
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
						@if(!isset($status))
						<tr>
							<td colspan="7" align="center">Belum ada data</td>
						</tr>
						@else
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
					@endif
				</table>
			</div>
		</div>
	</div>
</div>
@if(isset($status))
<div class="row status">
	<div class="col-xs-12">
		<div class="box box-info">
			<div class="box-header with-border">
				<h3 class="box-title">Status Pembayaran</h3>
			</div>
			<div class="box-body">
				<div class="progress">
					<?php
						$tp = $dibayar / $tanggungan * 100;
						if($tp < 40)
						$class = 'danger';
						elseif($tp >=40 AND $tp < 75)
						$class = 'warning';	
						elseif($tp >= 75 and $tp < 100)
						$class="success";
						else
						$class="info";
					?>
					<div class="progress-bar progress-bar-{{ $class }}" role="progressbar" aria-valuenow="{{ $tp }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $tp }}%">
						<span class="">{{ round($tp, 1) }}%</span>
					</div>
				</div>
			</div>			
		</div>
	</div>
</div>
@endif
@endsection																																																																																																	
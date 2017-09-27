@extends('app')

@section('title')
Daftar Pembayaran
@endsection

@section('header')
<section class="content-header">
	<h1>
		Keuangan
		<small>Pembayaran</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Pembayaran</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Cari data pembayaran</h3>
	</div>
	<div class="box-body">
		<form method="post" action="{{ url('/pembayaran/search') }}">
			{!! csrf_field() !!}
			<div class="row">
				<div class="col-xs-12">
					<div class="input-group{{ $errors -> has('q') ? ' has-error' : '' }}">
						<input type="text" class="form-control" name="q" placeholder="Jenis pembayaran">
						<span class="input-group-btn">
							<button class="btn btn-info btn-flat" type="submit">Cari</button>
						</span>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>

<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Data pembayaran</h3>
	</div>
	<div class="box-body">
		@if(!$biaya -> count())
		<p class="text-muted">Belum ada data</p>
		@else
		<?php $c = 1; ?>
		<p class="text-muted">{{ $message or '' }}</p>
		<table class="table table-bordered table-striped">
			<tr>
				<th width="20px">No.</th>
				<th>Tanggal</th>
				<th>Pembayaran</th>
				<th>Periode</th>
				<th>Jumlah</th>
				<th>Kekurangan</th>
				<th>Status</th>
			</tr>	
			@foreach($biaya as $i)
			<tr>
				<td>{{ $c }}</td>
				<td>{{ $i -> tanggal }}</td>
				<td>{{ $i -> jenis }}</td>
				<td>
					<?php
						if($i -> jangka == '3') // bulanan
						{
							$p = explode('-', $i -> periode);
							echo config('custom.bulan')[$p[0]] . ' ' . $p[1];
						}
						elseif($i -> jangka == '4') // TA
						{
							echo $tapel[$i -> periode];
						}
						else
						{
							echo '-';
						}
					?>
				</td>
				<td>Rp {{ number_format($i -> jumlah, 2, ',', '.') }}</td>
				<td>Rp {{ number_format($i -> sisa_tanggungan, 2, ',', '.') }}</td>
				<td>
					@if($i -> lunas == 'n')
					<span class="label label-danger">Belum lunas</span>
					@else
					<span class="label label-success">Lunas</span>
					@endif
				</td>
			</tr>	
			<?php $c ++; ?>
			@endforeach
		</table>
		{!! $biaya -> render() !!}
		@endif
	</div>
</div>
@endsection					
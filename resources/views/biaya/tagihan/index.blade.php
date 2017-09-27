@extends('app')

@section('title')
Daftar Tagihan
@endsection

@section('content')
<h2>Daftar Tagihan <a href="{{route('biaya.tagihan.create') }}" class="btn btn-info" title="Buat Tagihan"><i class="fa fa-plus"></i></a></h2>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><strong>Cari data tagihan</strong></h3>
	</div>
	<div class="panel-body">
		<form method="post" action="/biaya/tagihan/search">
			{!! csrf_field() !!}
			<div class="row">
				<div class="col-xs-12">
					<div class="input-group">
						<input type="text" class="form-control" name="q" placeholder="NIM / Nama [jenis pembayaran]">
						<span class="input-group-btn">
							<button class="btn btn-primary" type="submit">Cari</button>
						</span>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
@if(!$biaya -> count())
Belum ada data
@else
<?php $c = 1; ?>
<p class="text-muted">{{ $message or '' }}</p>
<div class="row">
	<div class="col-xs-12">
		<table class="table table-bordered table-striped">
			<tr>
				<th width="20px">No.</th>
				<th>TA</th>
				<th>Tanggal</th>
				<th>NIM</th>
				<th>Nama</th>
				<th>Pembayaran</th>
				<th>Periode</th>
				<th>Jumlah</th>
				<th>Kekurangan</th>
				<th>Petugas</th>
				<th>Update</th>
			</tr>	
			@foreach($biaya as $i)
			<tr>
				<td>{{ $c }}</td>
				<td>{{ $i -> tapel -> nama }}</td>
				<td>{{ $i -> tanggal }}</td>
				<td>{{ $i -> nim }}</td>
				<td>{{ $i -> nama }}</td>
				<td>{{ $i -> jenis }}</td>
				<td>
					<?php
						if($i -> jangka == '3') // bulanan
						{
							$p = explode('-', $i -> periode);
							echo config('custom.bulan')[$p[0]] . ' ' . $p[1];
						}
					/* 	elseif($i -> jangka == '4') // TA
						{
							echo $tapel[$i -> periode];
						} */
						else
						{
							echo '-';
						}
					?>
				</td>
				<td>Rp {{ number_format($i -> jumlah, 2, ',', '.') }}</td>
				<td>Rp {{ number_format($i -> sisa_tanggungan, 2, ',', '.') }}</td>
				<td>{{ $i -> petugas -> authable -> nama}}</td>
				<td>
					@if($i -> lunas == 'n')
					<a href="{{ route('biaya.edit', $i -> id) }}" class="btn btn-xs btn-warning"><i class="fa fa-edit"></i> Update</a>
					@else
					<span class="label label-success">Lunas</span>
					@endif
				</td>
			</tr>	
			<?php $c ++; ?>
			@endforeach
		</table>
		{!! $biaya -> render() !!}
		</div>
		</div>
		@endif
		@endsection							
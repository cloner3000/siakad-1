@extends('app')

@section('title')
Transaksi Keuangan
@endsection

@section('content')
<h2>Transaksi Keuangan<a href="{{ route('transaksi.create') }}" class="btn btn-info" title="Tambah Transaksi"><i class="fa fa-plus"></i></a></h2>
@if(!$transaksi->count())
Belum ada data
@else
<?php 
	$c = 1; 
	$jenis_transaksi = [1 => 'Pemasukan', 'Pengeluaran'];
?>
<div class="row">
	<div class="col-xs-12 col-sm-8">
		<table class="table table-bordered">
			<tr>
				<th width="20px">No.</th>
				<th>Tanggal</th>
				<th>Jenis</th>
				<th>Jumlah</th>
				<th>Keterangan</th>
				<th>Petugas</th>
				<!--th></th-->
			</tr>	
			@foreach($transaksi as $i)
			<tr>
				<td>{{ $c }}</td>
				<td>{{ date('d M Y', strtotime($i -> tanggal)) }}</td>
				<td>{{ $jenis_transaksi[$i -> jenis] }}</td>
				<td>Rp {{ number_format($i -> jumlah, 2, ',', '.') }}</td>
				<td>{{ $i -> keterangan }}</td>
				<td>{{ $i -> petugas -> authable -> nama }}</td>
				<!--td>
					<a href="" class="btn btn-xs btn-success"><i class="fa fa-edit"></i> Edit</a>
				</td-->
			</tr>	
			<?php $c ++; ?>
			@endforeach
		</table>
		{!! $transaksi -> render() !!}
	</div>
</div>
@endif
@endsection
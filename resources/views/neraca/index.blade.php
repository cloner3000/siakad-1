@extends('app')

@section('title')
Neraca Keuangan
@endsection

@section('content')
<h2>Neraca Keuangan</h2>
@if(count($neraca) < 1)
Belum ada data
@else
<?php 
	$c = 1; 
?>
<div class="row">
	<div class="col-xs-12 col-sm-6">
		<table class="table table-bordered">
			<tr>
				<th width="20px">No.</th>
				<th>Tanggal</th>
				<th>Pemasukan</th>
				<th>Pengeluaran</th>
				<th>Saldo</th>
			</tr>	
			@foreach($neraca as $i)
			<tr>
				<td>{{ $c }}</td>
				<td>{{ $i -> tanggal }}</td>
				<td>Rp {{ number_format($i -> masuk, 2, ',', '.') }}</td>
				<td>Rp {{ number_format($i -> keluar, 2, ',', '.') }}</td>
				<td>Rp {{ number_format($i -> saldo, 2, ',', '.') }}</td>
			</tr>	
			<?php $c ++; ?>
			@endforeach
		</table>
	</div>
</div>
@endif
@endsection
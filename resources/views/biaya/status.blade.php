<!doctype HTML>
<html>
	<head>
		<title>Status Pembayaran</title>
		<style>
			body{
			padding: 20px;
			width: 21cm;
			height: 35.56cm;
			font-family: Tahoma;
			}
			.table{
			width: 100%;
			border-collapse: collapse;
			}
			.table th{
			padding: 7px;
			}
			.table td{
			padding: 0px 4px;
			}
			.table td, th{
			border: 1px solid black;
			}
		</style>
	</head>
	<body>
		<h2 style="text-align: center;">Status Pembayaran</h2>
		<table>
			<tr>
				<td>NIRM</td><td width="20">:</td><td>{{ $mahasiswa -> NIM }}</td>
			</tr>
			<tr>
				<td>Nama</td><td>:</td><td>{{ $mahasiswa -> nama }}</td>
			</tr>
			<tr>
				<td>PRODI / Program / Semester</td><td>:</td><td>{{ $mahasiswa -> prodi -> nama }} / {{ $mahasiswa -> kelas -> nama }} / {{ $mahasiswa -> semesterMhs }}</td>
			</tr>
		</table>
		<hr/>
		<?php $c = 1; $tanggungan = $dibayar = $sisa = 0;?>
		<table class="table">
			<thead>
				<tr>
					<th>No.</th>
					<th>Jenis Pembayaran</th>
					<th>Jumlah Bayar</th>
					<th>Sudah Bayar</th>
					<th>Sisa</th>
				</tr>
			</thead>
			<tbody>
				@foreach($status as $s)
				<tr>
					<td>{{ $c }}</td>
					<td>{{ $s -> nama }}</td>
					<td>Rp {{ number_format($s -> tanggungan, 0, ',', '.') }}</td>
					<td>Rp {{ number_format($s -> dibayar, 0, ',', '.') }}</td>
					<td>Rp {{ number_format($s -> sisa, 0, ',', '.') }}</td>
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
					<th>Rp {{ number_format($sisa, 0, ',', '.') }}</th>
				</tr>
			</tbody>
		</table>
		<p>
			{{ config('custom.profil.alamat.kabupaten') }}, {{ formatTanggal(date('Y-m-d')) }}<br/>
			{!! config('custom.ttd.biaya.status.kiri') !!}
		</p>
		<script>
			window.print();
		</script>
	</body>
</html>									
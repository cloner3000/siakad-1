<!doctype HTML>
<html>
	<head>
		<title>Rincian Biaya Mahasiswa</title>
		<script>
			table{
				border-collapse: collapse;
			}
			th, td{
				border: 1px solid black;
			}
		</script>
	</head>
	<body>
		<?php $c = 1; ?>
		<table>
			<tr>
				<th colspan="{{ (count($jenis2) + 2) }}" style="background-color: #00c0ef; color: #ffffff; text-align: center;">Rincian Biaya Pendidikan Mahasiswa {{ $title }}</th>
			</tr>
			<tr style="background-color: #367fa9; color: #ffffff;">
				<th>No</th>
				<th style="text-align: center;">Nama</th>
				@foreach($jenis2 as $k => $v)
				<th style="text-align: center;">{{ $v['nama'] }}</th>
				@endforeach
			</tr>
			<tr style="background-color: #ffff00;">
				<th colspan="2" style="text-align: center;">Tanggungan</th>
				@foreach($jenis2 as $k => $v)
				<td>{{ $v['tanggungan'] }}</td>
				@endforeach
			</tr>
			@foreach($rincian as $k => $v)
			<?php
				$id = explode('-', $k);
			?>
			<tr>
				<td>{{ $c }}</td>
				<td>{{ $id[1] }}</td>
				@foreach($v as $r)
				<td>@if($r -> dibayar > 0){{ $r -> dibayar }}@endif</td>
			@endforeach
			</tr>
			<?php $c++; ?>
			@endforeach		
		</table>
	</body>
</html>									
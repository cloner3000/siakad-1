<!doctype HTML>
<html>
	<head>
		<title>Rincian Biaya Mahasiswa</title>
		<style>
			body{
			padding: 5px;
			height: 21cm;
			width: 35.56cm;
			font-family: Tahoma;
			}
			table{
			width: 100%;
			font-size: 10px;
			border-collapse: collapse;
			}
			table th{
			
			}
			table td{
			padding: 0px 4px;
			}
			table td, th{
			border: 1px solid black;
			}
		</style>
	</head>
	<body>
		<?php $c = 1; ?>
		<table>
			<thead>
				<tr>
					<th colspan="{{ (count($jenis2) + 2) }}" style="font-size: 14px; text-transform: uppercase; padding: 3px; background-color: #00c0ef; color: #ffffff;">Rincian Biaya Pendidikan Mahasiswa {{ $title }}</th>
				</tr>
				<tr style="background-color: #367fa9; color: #ffffff;">
					<th width="20px">No</th>
					<th>Nama</th>
					@foreach($jenis2 as $k => $v)
					<th>{{ $v['nama'] }}</th>
					@endforeach
				</tr>
			</thead>
			<tbody>
				<tr style="background-color: #ffff00;">
					<th colspan="2" class="align-center">Tanggungan</th>
					@foreach($jenis2 as $k => $v)
					<td>{{ number_format($v['tanggungan'] , 0, ',', '.') }}</td>
					@endforeach
				</tr>
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
		<script>
			window.print();
		</script>
	</body>
</html>									
<!doctype HTML>
<html>
	<head>
		<title>Transkrip Merge</title>
		<style>
			table{
			border-collapse:collapse;
			}
			td, th{
			border: 1px solid black;
			}
		</style>
	</head>
	<body>
		<?php 
			$c=1; 
			$thmk = '';
			foreach($rdata['mk'] as $m)
			{
				$thmk .= '<th>' . $m -> nama . '</th>';
			}
		?>
		<table>
			<thead>
				<tr>
					<th>No</th>
					<th>Nama</th>
					<th>TTL</th>
					<th>NPM</th>
					<th>NIRM</th>
					<th>NIRL1</th>
					<th>NIRL2</th>
					{!! $thmk !!}
					<th>&nbsp;</th>
					{!! $thmk !!}
					<th>&nbsp;</th>
					{!! $thmk !!}
					<th>&nbsp;</th>
					<th>SKSN</th>
					<th>sks</th>
					<th>IP OTOMATIS</th>
					<th>IP MANUAL</th>
					<th>YUDISIUM</th>
					<th>JUDUL</th>
					<th>NO. IJA</th>
					<th>KD. IJA</th>
					<th>NO. AKTA</th>
					<th>KD. AKTA</th>
					<th>NO. LULUS</th>
				</tr>
			</thead>
			<tbody>
				@foreach($rdata['tmp'] as $k)
				<tr>
					<td>{{ $c }}</td>
					<td>{{ $k['nama'] }}</td>
					<td>{{ $k['ttl'] }}</td>
					<td>{{ $k['npm'] }}</td>
					<td>{{ $k['nirm'] }}</td>
					<td>{{ $k['nirl1'] }}</td>
					<td>{{ $k['nirl2'] }}</td>
					@foreach($rdata['mk'] as $m)
					<td>@if(isset($k['nilai'][$m -> nama])){{ $k['nilai'][$m -> nama] }}@endif</td>
					@endforeach
					<td>&nbsp;</td>
					<?php $tsks = 0; $tb = 0; ?>
					@foreach($rdata['mk'] as $m)
					<td>
						@if(isset($k['sks'][$m -> nama]))
						{{ $k['sks'][$m -> nama] }}
						<?php $tsks += intval($k['sks'][$m -> nama]); ?>
						@endif
					</td>
					@endforeach
					<td>{{ $tsks }}</td>
					@foreach($rdata['mk'] as $m)
					<td>
						@if(isset($k['nsks'][$m -> nama]))
						{{ $k['nsks'][$m -> nama] }}
						<?php $tb += $k['nsks'][$m -> nama]; ?>
						@endif
					</td>
					@endforeach
					<td>{{ $tb }}</td>
					<td></td>
					<td>{{ $tsks }}</td>
					<td>{{ $y = round($tb/$tsks, 2) }}</td>
					<td></td>
					<td>{{ predikat($y) }}</td>
					<td>{{ $k['judul'] }}</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<?php $c++; ?>
				</tr>
				@endforeach
			</tbody>
			</table>
			</body>
			</html>														
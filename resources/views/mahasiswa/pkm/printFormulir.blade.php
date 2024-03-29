<!DOCTYPE html>
<html>
	<head>
		<title>Cetak Formulir PKM</title>
		<style>
			body{
			padding: 10px;
			width: 21cm;
			height: 35.56cm;
			}
			hr{
			margin: 1px;
			}			
			h1, h2, h3, h4, h5{
			margin: 0px;
			text-transform: uppercase;
			}
			img{
			max-width: 100%;
			}
			table{
			width: 100%;
			border-collapse: collapse;
			margin: 10px 0;
			}
			table.layout > tbody > tr > td{
			padding: 7px 5px;
			border: 1px solid black;
			}
			table.data{
			width: 97%;
			margin-left: 3%;
			}
			table.data td{
			padding: 8px 0;
			}
			#preview{
			display:block;
			width: 150px;
			border: 1px solid #999;
			}
			ol{
			margin: 0px;
			padding: 0px 15px;
			}
		</style>
	</head>
	<body>
		<table class="layout">
			<tbody>
				<tr>
					<td rowspan="2" width="70%">
						<!--img src="{{ asset('/images/header.png') }}" /-->
						<div style="text-align: center">
							<img src="{{ asset('/images/logo-staima.png') }}" width="80px"/>
							<h4>{{ config('custom.school.type') }}</h4>
							<h3>{{ config('custom.school.name') }}</h3>
							Jl. Cengger Ayam 25 Malang Telp/Fax: (0342)<br/>
							email: {{ config('custom.school.email') }} website: {{ config('custom.school.website') }}
							<h5>program studi</h5>
							@foreach($prodi as $p)<span> &#8226; {{ $p }}</span>@endforeach
						</div>
					</td>
					<td align="center">
						<h4>
							FORMULIR<br/>PENDAFTARAN PKM<br/>
							TAHUN {{ $pkm -> nama }}
						</h4>
					</td>
				</tr>
				<tr>
					<td align="center">
						<img id="preview" src="{{ url('/getimage/'.$mahasiswa -> foto) }}"></img>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<table class="data">
							<tr>
								<td width="20%">1. Program Studi</td><td width="1%">:</td><td width="70%">{{ $mahasiswa -> prodi -> strata }} {{ $mahasiswa -> prodi -> nama }} ({{ $mahasiswa -> kelas -> nama }})</td>
							</tr>		
							<tr>
								<td>2. NIM</td><td>:</td><td>{{ $mahasiswa -> NIM }}</td>
							</tr>			
							<tr>
								<td>3. NIRM</td><td>:</td><td>{{ $mahasiswa -> NIRM }}</td>
							</tr>			
							<tr>
								<td>4. Nama</td><td>:</td><td>{{ $mahasiswa -> nama }}</td>
							</tr>							
							<tr>
								<td>5. Jenis Kelamin</td><td>:</td><td>@if($mahasiswa -> jenisKelamin == 'L')<span>Pria</span>@else<span>Wanita</span>@endif</td>
							</tr>							
							<tr>
								<td>6. Tanggal Lahir</td><td>:</td><td>{{ $mahasiswa -> tglLahir }}</td>
							</tr>							
							<tr>
								<td>7. Tempat Lahir</td><td>:</td><td>{{ $mahasiswa -> tmpLahir }}</td>
							</tr>						
							<tr>
								<td>8. Kewarganegaraan</td><td>:</td><td>
									@if($mahasiswa -> statusWrgNgr == 2) {{ $mahasiswa -> kewarganegaraan -> nama }}
									@else {{ config('custom.pilihan.statusWrgNgr')[$mahasiswa -> statusWrgNgr] }}
									@endif
								</td>
							</tr>				
							<tr>
								<td>9. Agama</td><td>:</td><td>
									{{ config('custom.pilihan.agama')[$mahasiswa -> agama] }}
								</td>
							</tr>			
							<tr>
								<td>10. Status Sipil</td><td>:</td><td>
									{{ config('custom.pilihan.statusSipil')[$mahasiswa -> statusSipil] }}
								</td>
							</tr>							
							<tr>
								<td>11. Alamat Mahasiswa</td><td>:</td><td>{{ $alamat }}</td>
							</tr>														
							<tr>
								<td>12. Nomor HP</td><td>:</td><td>{{ $mahasiswa -> hp }}</td>
							</tr>											
							<tr>
								<td>13. Periode (tahun)</td><td>:</td><td>{{ substr($mahasiswa -> NIM, 0, 4) }}</td>
							</tr>											
							<tr>
								<td valign="top">14. Kemampuan Spesifik</td><td valign="top">:</td><td>
									<?php
										if($mahasiswa -> kemampuan != '')
										{
											$k = explode('[]', $mahasiswa -> kemampuan);
											echo '<ol>';
											foreach($k as $l)
											{
												if($l != '') echo '<li>' . $l . '</li>';
											}
											echo '</ol>';
										}
									?>
								</td>
							</tr>									
							<tr>
								<td valign="top">15. Kekurangan Spesifik</td><td valign="top">:</td><td>
									<?php
										if($mahasiswa -> kekurangan != '')
										{
											$k = explode('[]', $mahasiswa -> kekurangan);
											echo '<ol>';
											foreach($k as $l)
											{
												if($l != '') echo '<li>' . $l . '</li>';
											}
											echo '</ol>';
										}
									?>
								</td>
							</tr>					
						</table>
						<br/><br/><br/>
						<table class="data">
							<tr>
								<td width="70%">
									<br/>
									Ka. Prodi,
									<br/><br/><br/><br/><br/>
									({{ $mahasiswa -> prodi -> kaprodi }})<br/>
								</td>
								<td>
									Malang, {{ formatTanggal(date('Y-m-d')) }}<br/>
									Pendaftar,
									<br/><br/><br/><br/><br/>
									({{ $mahasiswa -> nama }})<br/>
								</td>
							</tr>	
						</table>
					</td>
				</tr>		
			</tbody>
		</table>		
		<script>
			window.print();
		</script>
	</body>
</html>																										
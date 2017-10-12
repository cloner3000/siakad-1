<?php 
	$rolename = strtolower(Auth::user() -> role -> name); 
	$userimage = Auth::user() -> authable -> foto !== '' ? '/getimage/' . Auth::user() -> authable -> foto : '/images/logo.png';
?>
@extends('app')

@section('title')
Selamat Datang di {{ config('custom.app.abbr') }}
@endsection

@section('styles')
<style>
	.col-sm-3{
	margin-bottom: 20px;
	}
	.menus{
	margin-top: 20px;
	}
	.menus a{
	color:#333;
	}
	.menus a:hover{
	color:#46a520;
	text-decoration:none;
	}	
	.menus a.sign-out:hover{
	color: #d22c26;
	}	
	.media{
	padding: 5px;
	}
	.media:hover{
	background-color: #f8f8f8;
	}
	
	.thumbnail{
	max-width: 150px;
	}
	
	.nav-tabs{
	margin-left: 5px;
	}
	.nav-info li.active a{
	background-color: #00c0ef !important;
	color: #fff !important;
	}
	
	.nav-success li.active a{
	background-color: #00a65a !important;
	color: #fff !important;
	}
</style>
<link rel="stylesheet" href="{{ url('css/morris.css') }}">
@endsection

@section('content')
@if(in_array(Auth::user() -> role_id, [1, 2]))
<div class="row">
	<div class="col-md-3 col-sm-6 col-xs-12">
		<div class="small-box bg-aqua">
            <div class="inner">
				<h3>{{ $counter['mahasiswa'] }}</h3>
				
				<p>Mahasiswa</p>
			</div>
            <div class="icon">
				<i class="ion ion-ios-people"></i>
			</div>
            <a href="{{ url('/mahasiswa') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
		</div>
	</div>
	
	<div class="col-md-3 col-sm-6 col-xs-12">
		<div class="small-box bg-green">
			<div class="inner">
				<h3>{{ $counter['dosen'] }}</h3>
				
				<p>Dosen</p>
			</div>
            <div class="icon">
				<i class="fa fa-briefcase"></i>
			</div>
            <a href="{{ url('/dosen') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
		</div>
	</div>
	<!-- /.col -->
	
	<!-- fix for small devices only -->
	<div class="clearfix visible-sm-block"></div>
	
	<div class="col-md-3 col-sm-6 col-xs-12">
		<div class="small-box bg-red">
			<div class="inner">
				<h3>{{ $counter['matkul'] }}</h3>
				
				<p>Mata Kuliah</p>
			</div>
            <div class="icon">
				<i class="fa fa-book"></i>
			</div>
            <a href="{{ url('/matkul') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
		</div>
	</div>
	<!-- /.col -->
	<div class="col-md-3 col-sm-6 col-xs-12">
		<div class="small-box bg-yellow">
			<div class="inner">
				<h3>{{ $counter['kelaskuliah'] }}</h3>
				
				<p>Kelas Kuliah</p>
			</div>
            <div class="icon">
				<i class="fa fa-building-o"></i>
			</div>
            <a href="{{ url('/matkul/tapel') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
		</div>
	</div>
	<!-- /.col -->
</div>

<div class="row">
	<div class="col-sm-6">
		<ul class="nav nav-tabs nav-info">
			<li class="active"><a href="#jk_mhs" data-toggle="tab">Jenis Kelamin</a></li>
			<li><a href="#asal_mhs" data-toggle="tab">Asal</a></li>
			<li><a href="#tab_pk_ortu_mhs" data-toggle="tab">Pek. Ortu</a></li>
			<li><a href="#tab_mhs" data-toggle="tab">Mahasiswa</a></li>
			<li><a href="#tab_status_mhs" data-toggle="tab">AKM</a></li>
			<li><a href="#tab_prodi_mhs" data-toggle="tab">Prodi</a></li>
		</ul>
		<div class="tab-content">
			
			<div class="tab-pane fade in active" id="jk_mhs">
				<div class="box box-info">
					<div class="box-header with-border">
						<h3 class="box-title"><i class="fa fa-bar-chart"></i> Jumlah Mahasiswa Sesuai Jenis Kelamin Per-angkatan</h3>
					</div>
					<div class="box-body chart-responsive">
						<canvas id="chart_jk_angk" height="200" />
					</div>
				</div>
			</div>
			
			<div class="tab-pane fade" id="tab_mhs">
				<div class="box box-info">
					<div class="box-header with-border">
						<h3 class="box-title"><i class="fa fa-line-chart"></i> Jumlah Mahasiswa Baru & Lulusan</h3>
					</div>
					<div class="box-body chart-responsive">
						<div class="chart" id="chart1" style="height: 340px;"></div>
					</div>
				</div>
			</div>
			
			
			
			<div class="tab-pane fade" id="asal_mhs">
				<div class="box box-info">
					<div class="box-header with-border">
						<h3 class="box-title"><i class="fa fa-bar-chart-o"></i> Daerah Asal Mahasiswa</h3>
					</div>
					<div class="box-body chart-responsive">
						<canvas id="chart3" height="200" />
					</div>
				</div>
			</div>
			
			<div class="tab-pane fade" id="tab_status_mhs">
				<div class="box box-info">
					<div class="box-header with-border">
						<h3 class="box-title"><i class="fa fa-pie-chart"></i> Status Mahasiswa</h3>
					</div>
					<div class="box-body">
						<div class="chart" id="chart2" style="height: 340px;"></div>
					</div>
				</div>
			</div>
			
			<div class="tab-pane fade" id="tab_prodi_mhs">
				<div class="box box-info">
					<div class="box-header with-border">
						<h3 class="box-title"><i class="fa fa-pie-chart"></i> Mahasiswa Per-Prodi</h3>
					</div>
					<div class="box-body">
						<div class="chart" id="prodi_mhs" style="height: 340px;"></div>
					</div>
				</div>
			</div>
			
			<div class="tab-pane fade" id="tab_pk_ortu_mhs">
				<div class="box box-info">
					<div class="box-header with-border">
						<h3 class="box-title"><i class="fa fa-pie-chart"></i> Pekerjaan Orang Tua Mahasiswa</h3>
					</div>
					<div class="box-body chart-responsive">
						<canvas id="chart4" height="200" />
					</div>
				</div>
			</div>
			
		</div>
	</div>
	
	<div class="col-sm-6">
		
		<ul class="nav nav-tabs nav-success">			
			<li class="active"><a href="#tab_status_dosen" data-toggle="tab">Status Dosen</a></li>
			<li><a href="#tab_png_dosen" data-toggle="tab">Penugasan Dosen</a></li>
			<li><a href="#tab_jk_dosen" data-toggle="tab">Jenis Kelamin Dosen</a></li>
		</ul>
		
		<div class="tab-content">
			
			<div class="tab-pane fade in active" id="tab_status_dosen">
				<div class="box box-success">
					<div class="box-header with-border">
						<h3 class="box-title"><i class="fa fa-pie-chart"></i> Status Dosen</h3>
					</div>
					<div class="box-body chart-responsive">
						<canvas id="st_dosen" height="200" />
					</div>
				</div>
			</div>
			
			<div class="tab-pane fade" id="tab_png_dosen">
				<div class="box box-success">
					<div class="box-header with-border">
						<h3 class="box-title"><i class="fa fa-pie-chart"></i> Penugasan Dosen</h3>
					</div>
					<div class="box-body chart-responsive">
						<canvas id="png_dosen" height="200" />
					</div>
				</div>
			</div>
			
			<div class="tab-pane fade" id="tab_jk_dosen">
				<div class="box box-success">
					<div class="box-header with-border">
						<h3 class="box-title"><i class="fa fa-pie-chart"></i> Jumlah Dosen Sesuai Jenis Kelamin </h3>
					</div>
					<div class="box-body chart-responsive">
						<canvas id="jk_dosen" height="200" />
					</div>
				</div>
			</div>
			
		</div>
	</div>
</div>

@else
<div class="row">
	<div class="col-md-9">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h1 class="box-title" style="font-size: 30px;">Selamat Datang</h1>
			</div>
			<!-- /.box-header -->
			<div class="box-body" style="font-size: 17px;">
				Selamat Datang di Sistem Administrasi Akademik (SIAKAD) STAI Ma'had Aly Al-Hikam Malang. SIAKAD adalah sistem yang memungkinkan para civitas akademika STAI Ma'had Aly Al-Hikam Malang menerima
				dan mengirim informasi dengan cepat melalui internet. Sistem ini diharapkan dapat  memberi kemudahan setiap civitas akademika untuk melakukan aktivitas-aktivitas akademik dan proses belajar mengajar.
				Selamat menggunakan fasilitas ini.
			</div>
		</div>
	</div>
	
	<div class="col-md-3">		
		<!-- Profile Image -->
		<div class="box box-primary">
			<div class="box-body box-profile">
				<img class="profile-user-img img-responsive img-circle" src="{{ url($userimage) }}" alt="User profile picture" style="width: 100px; height: 100px">
				
				<h3 class="profile-username text-center">{{ Auth::user() -> authable -> nama }}</h3>
				
				<p class="text-muted text-center">{{ Auth::user() -> role -> name }}</p>
				
				<!--ul class="list-group list-group-unbordered">
					<li class="list-group-item">
					<b>Followers</b> <a class="pull-right">1,322</a>
					</li>
					<li class="list-group-item">
					<b>Following</b> <a class="pull-right">543</a>
					</li>
					<li class="list-group-item">
					<b>Friends</b> <a class="pull-right">13,287</a>
					</li>
				</ul-->
				
				<a href="{{ url('/profil') }}" class="btn btn-primary btn-block"><b>Profil</b></a>
			</div>
			<!-- /.box-body -->
		</div>
		<!-- /.box --> 
	</div>
	
</div>
@endif
<div class="box box-success">
	<div class="box-header with-border">
		<h1 class="box-title"><i class="fa fa-bullhorn"></i> Pengumuman</h1>
		<div class="box-tools pull-right">
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
			</button>
			<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
		</div>
	</div>
	<!-- /.box-header -->
	<div class="box-body">
		@foreach($informasi as $i)
		<div class="media">
			<small class="pull-right">
				{{ formatTanggalWaktu($i -> updated_at) }}
			</small>
			<div class="media-body">
				<h4 class="media-heading">{{ $i -> judul }} </h4>
				{{ str_limit(strip_tags($i -> isi), 100, '') }} <a href="{{ url('/info/' . $i -> id) }}"> selengkapnya ...</a> 
			</div>
		</div>
		@endforeach
	</div>
</div>

@if(Auth::user() -> role_id > 2)
<?php $c = 1; ?>
<div class="box box-danger">
	<div class="box-header with-border">
		<h1 class="box-title"><i class="fa fa-download"></i> Download</h1>
		<div class="box-tools pull-right">
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
			</button>
			<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
		</div>
	</div>
	<div class="box-body">
		<table class="table table-bordered">
			<tr>
				<th>No.</th>
				<th>Tanggal</th>
				<th>Nama</th>
				<th>File</th>
			</tr>
			@foreach($files as $file)
			<tr>
				<td>{{ $c }}</td>
				<td>{{ formatTanggal(substr($file -> created_at, 0, 10)) }}</td>
				<td>{{ $file -> nama }} <span style="font-size: 10px;">{{ $file -> ukuran }}</span></td>
				<td>
					<a href="{{ url('/getfile/' . $file -> namafile) }}" class="btn btn-info btn-xs btn-flat"><i class="fa fa-download"></i> Download</a>
				</td>
			</tr>
			<?php $c++; ?>
			@endforeach
		</table>
	</div>
</div>
@endif

@if(Auth::user() -> role_id == 1) 
<div class="box box-warning">
	<div class="box-header with-border">
		<h3 class="box-title"><i class="fa fa-server"></i> Server information</h3>
		<div class="box-tools pull-right">
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
			</button>
		</div>
	</div>
	<div class="box-body" style="display: block;">
		<?php
			$info = \Cache::get('configs');
		?>
		Apache: {{ $info['server']['apache'] }}<br/>
		PHP: {{ $info['server']['php'] }}<br/>
		MySQL:  {{ $info['server']['mysql'] }}<br/>
	</div>
	<!-- /.box-body -->
</div>
<!-- /.box -->
<!-- Root -->
@endif
@endsection

@section('scripts')
<script src="{{ asset('/js/raphael.min.js') }}"></script>
<script src="{{ asset('/js/morris.min.js') }}"></script>
<script>
	$(function () {
		"use strict";
		
		// LINE CHART
		var chart_mhs = new Morris.Line({
			element: 'chart1',
			resize: true,
			data: [
			@foreach($per_angkatan as $k => $v)
			{y: '{{ $k }}', masuk: {{ $v }}, lulus: {{ $lulusan[$k] }}},
			@endforeach
			],
			xkey: 'y',
			ykeys: ['masuk', 'lulus'],
			labels: ['Mahasiswa', 'Lulusan'],
			lineColors: ['#3c8dbc', '#f35800'],
			hideHover: 'auto'
		});
		
		//DONUT CHART
		<?php $s = config('custom.pilihan.statusMhs'); ?>
		var chart_status_mhs = new Morris.Donut({
			element: 'chart2',
			resize: true,
			colors: ["#00a65a", "#3c8dbc", "#f35800", "#00c0ef",  "#f39c12", "#dd4b59", "#000000", "#ffcb00", "#9933cc", "#777777"],
			data: [
			@foreach($status as $k => $v)
			{label: '{{ $s[$k] }}', value: {{ $v }}, id: {{ $k }}},
			@endforeach
			],
			hideHover: 'auto'
			}).on('click', function(i, x){
			window.location.href = '{{ url('/mahasiswa/filter') }}' +  '?_token=' + '{{ csrf_token() }}' + '&status=' + x.id;
		});
		
		// mahasiswa per prodi
		var chart_prodi_mhs = new Morris.Donut({
			element: 'prodi_mhs',
			resize: true,
			colors: ["#00a65a", "#3c8dbc", "#f35800", "#00c0ef",  "#f39c12", "#dd4b59", "#000000", "#ffcb00", "#9933cc", "#777777"],
			data: [
			@if(count($mhs_prodi) > 0)
			@foreach($mhs_prodi as $k => $v)
			{label: '{{ $k }}', value: {{ $v }}},
			@endforeach
			@endif
			],
			hideHover: 'auto'
		});
		
		//FIX -- http://stackoverflow.com/a/38313151/6934844
		$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
			var href = $(this).attr('href');
			switch(href){
				case '#tab_mhs':
				chart_mhs.redraw();
				break;
				
				case '#tab_status_mhs':
				chart_status_mhs.redraw();
				break;
				
				case '#tab_prodi_mhs':
				chart_prodi_mhs.redraw();
				break;
				
			}
			$('svg').css({ width: '100%' });
		});
	});
</script>
<script src="{{ asset('/js/ChartJS.bundle.min.js') }}"></script>
<script>	
	$(function () {
		var randomColorFactor = function() {
			return Math.round(Math.random() * 255);
		};
		var randomColor = function() {
			return 'rgb(' + randomColorFactor() + ',' + randomColorFactor() + ',' + randomColorFactor() + ')';
		};
		
		//http://stackoverflow.com/a/38797604
		var pieOptions = {
			events: false,
			responsive: true,
			animation: {
				duration: 500,
				easing: "easeOutQuart",
				onComplete: function () {
					var ctx = this.chart.ctx;
					ctx.font = Chart.helpers.fontString(17, 'normal', Chart.defaults.global.defaultFontFamily);
					ctx.textAlign = 'center';
					ctx.textBaseline = 'bottom';
					
					this.data.datasets.forEach(function (dataset) {
						
						for (var i = 0; i < dataset.data.length; i++) {
							var model = dataset._meta[Object.keys(dataset._meta)[0]].data[i]._model,
							total = dataset._meta[Object.keys(dataset._meta)[0]].total,
							mid_radius = model.innerRadius + (model.outerRadius - model.innerRadius)/2,
							start_angle = model.startAngle,
							end_angle = model.endAngle,
							mid_angle = start_angle + (end_angle - start_angle)/2;
							
							var x = mid_radius * Math.cos(mid_angle);
							var y = mid_radius * Math.sin(mid_angle);
							
							ctx.fillStyle = '#fff';
							if (i == 3){ // Darker text color for lighter background
								ctx.fillStyle = '#444';
							}
							
							var val = dataset.data[i];
							var percent = String(Math.round(val/total*100)) + "%";
							
							if(val != 0) {
								ctx.fillText(dataset.data[i], model.x + x, model.y + y);
								// Display percent in another line, line break doesn't work for fillText
								ctx.fillText(percent, model.x + x, model.y + y + 15);
							}
						}
					});               
				}
			}
		};
		
		
		/* prov */
		var configBar = {
			type: 'bar',
			data: {
				datasets: [{
					label: "Jumlah",
					data: [
					@foreach($prov as $p => $j)
					{{ $j }},
					@endforeach
					],
					backgroundColor: [
					@foreach($prov as $p => $j)
					randomColor(),
					@endforeach
					]
				}],
				labels: [
				@foreach($prov as $p => $j)
				"{{ $p }}",
				@endforeach
				]
			},
			options: {
				responsive: true,
				scales: {
					xAxes: [{
						ticks: {
							autoSkip: false
						}
					}]
				},
				legend: {
					display: false
				}
			}
		};
		var ctxBar = document.getElementById("chart3").getContext("2d");
		window.myBar = new Chart(ctxBar, configBar);
		/* prov */
		
		/* pk_ortu */
		var config = {
			type: 'pie',
			data: {
				datasets: [{
					data: [
					@foreach($pk_ortu as $k => $v)
					{{ $v }},
					@endforeach
					],
					backgroundColor: [
					"#57C5E9",
					"#46BFBD",
					"#FDB45C",
					"#949FB1",
					"#4D5360",
					"#A1E24C",
					"#50FFDF",
					"#F7464A",
					"#EB00DB",
					"#00992E",
					"#A114FF",
					"#FF4405",
					"#001361",
					"#33FF0F",
					],
				}],
				labels: [
				@foreach(config('custom.pilihan.pekerjaanOrtu') as $pk)
				"{{ $pk }}",
				@endforeach
				]
				},
				options: {
				responsive: true
				}
				};		
				var ctx = document.getElementById("chart4").getContext("2d");
				window.myPie = new Chart(ctx, config);
				/* pk_ortu */
				
				
				/* jk_angk */
				var jk_angk_data = {
				labels: [
				@if(count($jk_angkatan) > 0)
				@foreach($jk_angkatan as $k => $v)
				"{{ $k }}",
				@endforeach
				@endif
				],
				datasets: [{
				label: 'Laki-laki',
				backgroundColor: "rgba(0,192,239,0.5)",
				yAxisID: "y-axis-1",
				data: [
				@if(count($jk_angkatan) > 0)
				@foreach($jk_angkatan as $k => $v)
				{{ $v['L'] }},
				@endforeach
				@endif
				]
				}, {
				label: 'Perempuan',
				backgroundColor: "rgba(235,0,219,0.5)",
				yAxisID: "y-axis-2",
				data: [
				@if(count($jk_angkatan) > 0)
				@foreach($jk_angkatan as $k => $v)
				{{ $v['P'] }},
				@endforeach
				@endif
				]
				}]			
				};
				
				var xxx = {
				type: 'bar',
				data: jk_angk_data, 
				options: {
				responsive: true,
				hoverMode: 'index',
				hoverAnimationDuration: 400,
				stacked: false,
				scales: {
				yAxes: [{
				type: "linear", // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
				display: true,
				position: "left",
				id: "y-axis-1",
				}, {
				type: "linear", // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
				display: true,
				position: "right",
				id: "y-axis-2",
				gridLines: {
				drawOnChartArea: false
				}
				}],
				}
				}
				};
				var ctx_chart_jk_angk = document.getElementById("chart_jk_angk").getContext("2d");
				window.myBar = new Chart(ctx_chart_jk_angk, xxx);
				/* jk_angk */
				
				/* jk_dosen */
				var jk_dosen_config = {
				type: 'pie',
				data: {
				datasets: [{
				data: [
				@if(count($jk_dosen) > 0)
				@foreach($jk_dosen as $k => $v)
				{{ $v }},
				@endforeach
				@endif
				],
				backgroundColor: [
				"rgba(0,192,239,0.5)",
				"rgba(235,0,219,0.5)"
				],
				}],
				labels: ['Laki-laki', 'Perempuan']
				},
				options: pieOptions
				};		
				var jk_dosen_ctx = document.getElementById("jk_dosen").getContext("2d");
				window.myPie = new Chart(jk_dosen_ctx, jk_dosen_config);
				/* jk_dosen */
				
				/* st_dosen */
				var st_dosen_config = {
				type: 'pie',
				data: {
				datasets: [{
				data: [
				@if(count($st_dosen) > 0)
				@foreach($st_dosen as $v)
				{{ $v }},
				@endforeach
				@endif
				],
				backgroundColor: [
				"rgba(0,153,46,1)",
				"rgba(161,226,76,1)",
				"rgba(148,159,177,1)"
				], 
				}],
				labels: ['Dosen Tetap', 'Dosen Tetap Ber-NIDN', 'Dosen Tidak Tetap']
				},
				options: pieOptions
				};		
				var st_dosen_ctx = document.getElementById("st_dosen").getContext("2d");
				window.myPie = new Chart(st_dosen_ctx, st_dosen_config);
				/* st_dosen */
				
				/* png_dosen */
				var png_dosen_config = {
				type: 'pie',
				data: {
				datasets: [{
				data: [
				@if(count($png_dosen) > 0)
				@foreach($png_dosen as $k => $v)
				{{ $v }},
				@endforeach
				@endif
				],
				backgroundColor: ["#00a65a", "#3c8dbc", "#f35800", "#00c0ef",  "#f39c12", "#dd4b59", "#000000", "#ffcb00", "#9933cc", "#777777"], 
				}],
				labels: [
				@if(count($png_dosen) > 0)
				@foreach($png_dosen as $k => $v)
				'{{ $k }}',
				@endforeach
				@endif
				]
				},
				options: pieOptions
				};		
				var png_dosen_ctx = document.getElementById("png_dosen").getContext("2d");
				window.myPie = new Chart(png_dosen_ctx, png_dosen_config);
				/* st_dosen */
				});
				</script>
				@endsection
								
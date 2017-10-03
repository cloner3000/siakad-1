<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="robots" content="noindex, nofollow">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="" />
		<title>{{ config('custom.app.name') }} | {{ config('custom.app.title') }}</title>
		<link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet">
		<link href="{{ asset('/css/font-awesome.min.css') }}" rel="stylesheet">
		<link href="{{ asset('/css/bs-callout.css') }}" rel="stylesheet">
		<link rel="stylesheet" href="{{ asset('css/AdminLTE.min.css') }}">
		<link rel="stylesheet" href="{{ asset('css/source-sans-pro.css') }}">
		<style>
			html {
			height: 100%;
			box-sizing: border-box;
			}
			*,
			*:before,
			*:after {
			box-sizing: inherit;
			}
			body {
			position: relative;
			margin: 0;
			padding-bottom: 6rem;
			min-height: calc(100% - 70px);
			}
			.logo{
			text-align:center;
			margin-bottom: 20px;
			}
			.logo img{
			width: 128px;
			}
			.inpt{
 			position: relative;
			}
			.inpt .form-control{
			padding-left: 36px;
			}
			.form-control-feedback{
			top: 7%;
			left: 15px;
			} 
			span.fa{
			color: #999999;
			}
			#footer{
			position: absolute;
			right: 0;
			bottom: 0;
			left: 0;
			padding: 1rem 0;
			text-align: center;
			font-size:10px;
			color:#555;
			}
			.form{
			display: -webkit-flex;
			display: flex;
			width: 700px;
			margin: 0px auto;
			-webkit-box-shadow: 0px 0px 10px 0px rgba(50, 50, 50, 1);
			-moz-box-shadow:    0px 0px 10px 0px rgba(50, 50, 50, 1);
			box-shadow:         0px 0px 10px 0px rgba(50, 50, 50, 1);
			}
			.form-control{
			box-shadow:none;
			border:none;
			border-radius: 0px;
			border-bottom: 1px solid black;
			padding: 2px 0;
			}
			.form-control:focus{
			box-shadow:none;
			}
			
			.left{
			-webkit-flex: 2;
			flex: 2;
			background-image:url("/images/front2.jpg");
			background-color: #161316;
			color: #dedede;
			padding: 28px 10px;
			text-align: right;
			}
			.left #name{
			font-weight: bold;
			font-size: 17px;
			}
			.left #title{
			line-height: 15px;
			font-size: 19px;
			}
			.left #address{
			font-size: 15px;
			}
			.right{
			-webkit-flex: 2;
			flex: 2;
			padding: 20px 25px 40px 25px;
			}
			@media (max-width: 768px) {
			.form{
			width: 100%;
			}
			.left{
			display:none;
			}
			}
		</style>
	</head>
	<body>
		{!! isOnmaintenis() !!}
		<div class="container"  style="margin-top:50px;">
			<div class="row">
				<div class="center-block logo">
					<img src="{{ url('/images/logo.png') }}" />
				</div>
			</div>
			<div class="form"  style="margin-top:30px;">
				<div class="left">
					<div id="name">{{ config('custom.app.name') }}</div>
					<div id="title">{{ config('custom.profil.nama') }}</div>
					<div id="address">{{ config('custom.profil.alamat.jalan') }} {{ config('custom.profil.alamat.kabupaten') }}</div>
				</div>
				<div class="right">
					<h4>Login</h4>
					@if(Session::has('message'))
					<div class="callout callout-info">
						<h4>Informasi</h4>
						<p>{{ Session::get('message') }}</p>
					</div>
					@endif
					@if($errors->any())
					<div class="callout callout-danger">
						<h4>Kesalahan</h4>
						@foreach($errors -> all() as $error)
						<p>{{ $error }}</p>
						@endforeach
					</div>
					@endif
					<form class="form-horizontal" role="form" method="POST" action="{{ url('/auth/login') }}">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						
						<div class="form-group">
							<label class="col-xs-12 sr-only">Username</label>
							<div class="col-xs-12 inpt">
								<input type="text" class="form-control" name="username" value="{{ old('username') }}" autofocus="autofocus" required="required" placeholder="NIM / Username">
								<span class="fa fa-user fa-2x form-control-feedback"></span>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-xs-12 sr-only">Password</label>
							<div class="col-xs-12 inpt">
								<input type="password" class="form-control" name="password" required="required"  placeholder="Kata kunci">
								<span class="fa fa-lock fa-2x form-control-feedback"></span>
							</div>
						</div>
						<div class="form-group">
							<div class="col-xs-12">
								<div class="checkbox">
									<label>
										<input type="checkbox" name="remember"> Remember Me
									</label>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-12">
								<button type="submit" class="btn btn-primary btn-block"><i class="fa fa-sign-in"></i> Masuk</button>
								@if(config('custom.user.reset-password') == 0) 
									<a style="text-decoration: line-through">Lupa Password?</a>
								@else
								<a href="{{ url('/password/username') }}">
									Lupa Password?
								</a>
								@endif
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="modal fade modal-info" id="about" tabindex="-1" role="dialog" aria-labelledby="about-title" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
				</div>
			</div>
		</div>
		<div id="footer">							
			<a data-toggle="modal" href="{{ url('/about') }}" data-target="#about">{{ config('custom.app.abbr') }} {{ config('custom.app.version') }}</a><br/>
			{{ config('custom.profil.singkatan') }}&nbsp;
			{{ config('custom.profil.nama') }}<br/>
			&copy; 2016 - 2017
		</div>
		<script src="{{ asset('/js/jquery-2.2.3.min.js') }}"></script>
		<script src="{{ asset('/js/bootstrap.min.js') }}"></script>
	</body>
</html>

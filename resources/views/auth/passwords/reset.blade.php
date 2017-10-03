<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>{{ config('custom.app.name') }} | {{ config('custom.app.title') }}</title>
		<link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet">
		<link rel="stylesheet" href="{{ asset('css/AdminLTE.min.css') }}">
		<style type="text/css">
			#footer{
			margin:50px auto;
			text-align:center;
			font-size:10px;
			color:#555;
			}
		</style>	
		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	<body>
		<div class="container" style="margin-top: 50px;">
			<div class="row">
				<div class="content col-xs-12">
					<div class="container">
						<div class="row">
							<div class="col-md-8 col-md-offset-2">
								@if(isset($message))
								<div class="callout callout-info">
									<h4>Informasi</h4>
									<p>{{ $message }}</p>
								</div>
								@endif
								@if(Session::has('message'))
								<div class="callout callout-info">
									<h4>Informasi</h4>
									<p>{{ Session::get('message') }}</p>
								</div>
								@endif
								@if(Session::has('error'))
								<div class="callout callout-danger">
									<h4>Kesalahan</h4>
									<p>{{ Session::get('error') }}</p>
								</div>
								@endif
								<div class="panel panel-default">
									<div class="panel-heading">Password Baru</div>
									
									<div class="panel-body">
										<form class="form-horizontal" role="form" method="POST" action="{{ url('/password/reset') }}">
											{{ csrf_field() }}
											{!! Form::hidden('username', $user -> username) !!}
											{!! Form::hidden('reset_token', $user -> reset_token) !!}
											<div class="form-group">
												<label class="col-md-4 control-label">Username</label>
												<div class="col-md-6">
													<p class="form-control-static">{{ $user->username }}</p>
												</div>
											</div>
											<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
												<label class="col-md-4 control-label">Password Baru</label>
												<div class="col-md-6">
													<input type="password" class="form-control" name="password" required="required" @if($locked) disabled @endif >
													@if ($errors->has('password'))
													<span class="help-block">
														{{ $errors->first('password') }}
													</span>
													@endif
												</div>
											</div>
											
											<div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
												<label class="col-md-4 control-label">Ulangi Password Baru</label>
												<div class="col-md-6">
													<input type="password" class="form-control" name="password_confirmation"  required="required" @if($locked) disabled @endif >
													@if ($errors->has('password_confirmation'))
													<span class="help-block">
														{{ $errors->first('password_confirmation') }}
													</span>
													@endif
												</div>
											</div>
											<div class="form-group">
												<div class="col-md-6 col-md-offset-4">
													<button type="submit" class="btn btn-primary btn-flat" @if($locked) disabled @endif >
														Simpan
													</button>
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- Scripts -->
		<script src="{{ asset('/js/jquery.min.js') }}"></script>
		<script src="{{ asset('/js/bootstrap.min.js') }}"></script>
		<div id="footer">
		<a data-toggle="modal" href="{{ url('/about') }}" data-target="#about">{{ config('custom.app.abbr') }} {{ config('custom.app.version') }}</a><br/>
		{{ config('custom.profil.singkatan') }}&nbsp;
		{{ config('custom.profil.nama') }}<br/>
		&copy; 2016 - 2017
		</div>
		</body>
		</html>
				
<html>
	<head>
		<link rel="stylesheet" href="{{ asset('css/source-sans-pro.css') }}">
		<title>{{ config('custom.app.abbr') }} {{ config('custom.app.version') }} - Maintenance mode</title>
		<style>
			body {
			margin: 0px auto;
			padding: 0;
			width: 80%;
			height: 100%;
			color: #000;
			display: table;
			font-weight: 100;
			font-family: 'Source Sans Pro';
			}
			
			.container {
			text-align: center;
			display: table-cell;
			vertical-align: middle;
			}
			
			.content {
			text-align: center;
			display: inline-block;
			}
			
			.title {
			font-size: 30px;
			margin-bottom: 40px;
			}
		</style>
	</head>
	<body>
		<div class="container">
			<div class="content">
				<div class="title">{{ maintenisMessage() }}</div>
			</div>
		</div>
	</body>
</html>

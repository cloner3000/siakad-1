@extends('app')

@section('content')
<style>
	.error {
	color:#d9534f;
	margin:80px auto;
	font-family:Verdana;
	width:265px;	
	}
	.error_title{
	text-align:center;
	}
	.error_main {
	font-weight: bold;
	line-height:59px;
	font-size: 122px;
	padding-top:6px;
	padding-bottom:11px;
	}
	.error_small{
	padding:2px 5px;
	text-align:right;
	line-height:18px;
	font-size: 18px;
	background-color:#d9534f;
	color: #fff;
	}
	.error_description{
	color:#d9534f;
	font-size:20px;
	letter-spacing:5px;
	}
</style>
<div class="error">
		<div class="error_title error_main">403</div>
		<div class="error_title error_small">ERROR</div>
		<div class="error_title error_description">FORBIDDEN</div>
</div>
@endsection

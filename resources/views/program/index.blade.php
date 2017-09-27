@extends('app')

@section('title')
Program Kerja 
@endsection

@section('styles')

@endsection

@section('content')
<div class="row">
	<div class="col-sm-12">
		{!! $program -> program !!}
		<hr/>
		<div><a href="/program/edit" class="btn btn-success btn-xl"><i class="fa fa-pencil"></i> Edit Program Kerja</a></div>			
	</div>			
</div>				
@endsection
@extends('app')

@section('title')
Program Kerja
@endsection

@section('styles')
<link href="/summernote/summernote.css" rel="stylesheet">
@endsection

@section('scripts')
<script src="/summernote/summernote.min.js"></script>
<script>
	$(document).ready(function() {
		$('#summernote').summernote({
			minHeight: 300, 
			maxHeight: null, 
			focus: true,
			toolbar: [
			['style', ['bold', 'italic', 'underline', 'clear']],
			['font', ['strikethrough', 'superscript', 'subscript']],
			['fontsize', ['fontname', 'fontsize']],
			['color', ['color']],
			['para', ['ul', 'ol', 'paragraph']],
			['height', ['height']]
			]
		});
	});
	$(document).on('click', '#simpan', function(){
		var content = $('#summernote').summernote('code');
		$('#program').val(content);
		$('#program-form').submit();
	});
</script>
@endsection

@section('content')
<h2>Buat Program Kerja</h2>
<div class="row">
	<div class="col-sm-9">
		<form method="POST" action="{{ route('program.edit') }}" accept-charset="UTF-8" class="form-horizontal" id="program-form" role="form">
		<div id="summernote">{!! $program -> program !!}</div>
		<input type="hidden" name="program" id="program" >
		{!! csrf_field() !!}
		<button class="btn btn-primary" id="simpan"><i class="fa fa-floppy-o"></i> Simpan</button>
		{!! Form::close() !!}
	</div>
</div>
@endsection
@section('styles')
<style>
	/* input file - http://tympanus.net/codrops/2015/09/15/styling-customizing-file-inputs-smart-way/ */
	.upload {
	width: 0.1px;
	height: 0.1px;
	opacity: 0;
	overflow: hidden;
	position: absolute;
	z-index: 122;
	}
	.upload + label {
    display: inline-block;
	cursor: pointer;
	}
	.upload:focus + label {
	outline: 1px dotted #000;
	outline: -webkit-focus-ring-color auto 5px;
	}
	.upload + label * {
	pointer-events: none;
	}
	
	#preview{
	display:block;
	width: 200px;
	padding: 5px;
	margin-bottom: 15px;
	border: 1px solid #999;
	}
</style>
@endsection

@section('scripts')
<script src="{{ asset('/js/jquery.form.min.js') }}"></script>
<script>
	$(document).on('change', '#image', function(){
		
		$('#upload-icon').removeClass('fa-search');
		$('#upload-icon').addClass('fa-hourglass-o');
		$('#upload-icon').addClass('fa-spin');
		$('#filename').text('Sedang memproses...');
		
		$('form#upload').submit();
	});
	$('form#upload').ajaxForm({
		beforeSend: function() {
			
		},
		success: function(data) {
			if(!data.success)
			{
				$('#upload-label').removeClass('btn-default');
				$('#upload-label').addClass('btn-danger');
				$('#upload-icon').removeClass('fa-search');
				$('#upload-icon').addClass('fa-times');
				$('#filename').text('Error');
			}
			else
			{
				$('#preview').attr('src', '/getimage/' + data.filename);
				if($('#upload-icon').hasClass('fa-times')) 
				{
					$('#upload-icon').removeClass('fa-times');
					$('#upload-label').removeClass('btn-danger');
					$('#upload-label').addClass('btn-default');
				}
				$('#foto').val(data.filename);
			}
		},
		complete: function(xhr) {
			$('#upload-icon').removeClass('fa-hourglass-o');
			$('#upload-icon').removeClass('fa-spin');
			$('#upload-icon').addClass('fa-search');
			$('#filename').text('{{ $label or "Pilih file gambar..."}}');
		},
		error: function(XMLHttpRequest, textStatus, errorThrown){
			alert('Terjadi kesalahan: ' + errorThrown);
		}
	});  
	
</script>
@endsection

<img id="preview" src="@if(isset($foto) and $foto != '') /getimage/{{ $foto }} @else /images/{{ $default_image }} @endif"></img>
<div class="form-group">
	<input id="image" class="upload" data-multiple-caption="Terdapat {count} file terpilih" name="image" type="file">
	<label for="image" class="btn btn-default btn-flat" id="upload-label"><i class="fa fa-search" id="upload-icon"></i> <span id="filename">{{ $label or 'Pilih file gambar...' }}</span></label>
	<input type="hidden" name="width" value="300">
	<input type="hidden" name="height" value="400">
</div>
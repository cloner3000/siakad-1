
@section('scripts2')
<script src="{{ asset('/js/jquery.inputmask.bundle.min.js') }}"></script>
<script>
	$(function(){
		$(".date").inputmask("dd-mm-yyyy",{"placeholder":"dd-mm-yyyy"});
		$(".year").inputmask("y",{"placeholder":"yyyy"});
	});
</script>
@endsection

<div class="form-group">
	{!! Form::label('tahun1', 'Tahun:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-8">
		<div style="display:inline-block;">
			<?php
				if(isset($pkm))
				{
					$tahun = explode('/', $pkm -> nama);		
				}
				else
				{
				$tahun = [null, null];	
				}
			?>
			{!! Form::text('tahun1', $tahun[0], array('class' => 'form-control year')) !!}
		</div>
		/
		<div style="display:inline-block;">
			{!! Form::text('tahun2', $tahun[1], array('class' => 'form-control year')) !!}
		</div>
	</div>
</div>
<div class="form-group">
	{!! Form::label('tanggal_mulai', 'Tanggal Mulai', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-3">
		<?php $tanggal_mulai = isset($pkm -> tanggal_mulai) ? date('d-m-Y', strtotime($pkm -> tanggal_mulai)) : null; ?>
		{!! Form::text('tanggal_mulai', $tanggal_mulai, array('class' => 'form-control date', 'placeholder' => 'Tanggal Mulai PKM', 'required' => 'required')) !!}
	</div>
</div>
<div class="form-group">
{!! Form::label('tanggal_selesai', 'Tanggal Selesai', array('class' => 'col-sm-3 control-label')) !!}
<div class="col-sm-3">
	<?php $tanggal_selesai = isset($pkm -> tanggal_selesai) ? date('d-m-Y', strtotime($pkm -> tanggal_selesai)) : null; ?>
	{!! Form::text('tanggal_selesai', $tanggal_selesai, array('class' => 'form-control date', 'placeholder' => 'Tanggal Selesai PKM', 'required' => 'required')) !!}
	</div>
	</div>
	<div class="form-group">
	{!! Form::label('tempat', 'Tempat:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-7">
		{!! Form::textarea('tempat', null, array('class' => 'form-control', 'placeholder' => 'Tempat PKM', 'required' => 'required', 'rows' => '3')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('daftar', 'Pendaftaran peserta:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-5">
		<?php
			foreach(['y' => 'Buka', 'n' => 'Tutup'] as $k => $v) 
			{
				echo '<label class="radio-inline">';
				echo '<input type="radio" name="daftar" ';
				if(isset($pkm) and $k == $pkm -> daftar) echo 'checked="checked" ';
				echo 'value="'. $k .'"> '. $v .'</label>';
			}
		?>
	</div>
</div>
<div class="form-group">
	<div class="col-sm-offset-3 col-sm-9">
		<button class="btn btn-primary btn-flat {{ $btn_type }}" type="submit" id="post"><i class="fa fa-floppy-o"></i> Simpan</button>
	</div>		
</div>	
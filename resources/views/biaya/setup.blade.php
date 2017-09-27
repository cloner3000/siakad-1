@extends('app')

@section('title')
Biaya Kuliah
@endsection

@push('scripts')
<script src="{{ url('/js/jquery.form.min.js') }}"></script>
<script src="{{ asset('/js/jquery.inputmask.bundle.min.js') }}"></script>
<script>
	var loader = '<i class="fa fa-spinner fa-spin loader"></i>';
	$(function(){
		$(".currency").inputmask('999.999.999', { numericInput: true, autoUnmask: true, removeMaskOnSubmit: true, unmaskAsNumber: true });
	});
	
	$(document).on('click', '.btn-submit', function(){
		$(this).after(loader);	
		$('form#form-submit').submit();
		
	});
	$('form#form-submit').ajaxForm({
		beforeSend: function() {		
			$('.origin').closest('.input-group').append(loader);	
			
		},
		success: function(data) {			
			$('.loader').remove();
			$('.origin').removeClass('origin');
			$('#total').text(data.total);
		},
		error: function(XMLHttpRequest, textStatus, errorThrown){
			console.log('Terjadi kesalahan: ' + errorThrown);
		}
	});  
	$(document).on('keydown', '.biaya', function(e){
		if(e.which == 13)
		{
			$(this).addClass('origin');
			$('.btn-submit').click();
		}
	});
	
	$(document).on('click', '.btn-filter', function(){
		window.location.href="{{ url('/biaya/setup') }}/" + $('select[name=angkatan]') .val() + "/" + $('select[name=prodi_id]') .val() + "/" + $('select[name=program_id]') .val() + "/" + $('input[name=jenisPembayaran]:checked') .val();
	});
</script>
@endpush

@push('styles')
<style>
	/* .status{
	position: fixed;
	top: 5px;
	left: 50%;
	z-index: 9999999;
	padding: 5px 9px;
	border-radius: 5px;
	display:none;
	color: #FFF;
	}
	.loading{
	background-color: #f39c12;
	}
	.success{
	background-color: #5CB85C;
	}
	.failed{
	background-color: #D9534F;
	} */
	
	.input-group{
	position: relative;
	}
	.loader{
	color: #f00900;
	position: absolute;
	z-index: 999;
	top: 10px;
	right: 50%;
	}
</style>
@endpush

@section('header')
<section class="content-header">
	<h1>
		Keuangan
		<small>Setup Biaya Kuliah</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Setup Biaya Kuliah</li>
	</ol>
</section>
@endsection

@section('content')
<form method="POST" action='{{ url("/biaya/submit") }}' id="form-submit">
	{{ csrf_field() }}
	<div class="row">
		<div class="col-md-3">
			<div class="box box-warning">
				<div class="box-header with-border">
					<h3 class="box-title">Filter</h3>
				</div>
				<div class="box-body">
					<div role="form">
						<div class="form-group">
							<label for="angkatan">Angkatan</label>
							{!! Form::select('angkatan', $angkatan, $data['angkatan'], ['class' => 'form-control']) !!}
						</div>
						<div class="form-group">
							<label for="prodi_id">PRODI</label>
							{!! Form::select('prodi_id', $prodi, $data['prodi_id'], ['class' => 'form-control']) !!}
						</div>
						<div class="form-group">
							<label for="program_id">Program</label>
							{!! Form::select('program_id', $program, $data['program_id'], ['class' => 'form-control']) !!}
						</div>
						<div class="form-group">
							<label for="jenisPembayaran">Jenis</label>
							<div>
								<?php
									foreach(config('custom.pilihan.jenisPembayaran') as $k => $v) 
									{
										echo '<label class="radio-inline">';
										echo '<input type="radio" name="jenisPembayaran" ';
										if($k == $data['jenisPembayaran']) echo 'checked="checked" ';
										echo 'value="'. $k .'"> '. $v .'</label>';
									}
								?>
							</div>
						</div>
						<button class="btn btn-warning btn-flat btn-filter" type="button"><i class="fa fa-filter"></i> Filter</button>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-9">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Biaya Kuliah</h3>
				</div>
				<div class="box-body">
					@if(!$jenis -> count())
					<p class="help-block">Masukkan data <a href="{{ url('/jenisbiaya') }}"> Jenis Pembayaran</a> terlebih dahulu.</p>
					@else
					<?php $c = 1; $total = 0;?>
					<table class="table table-bordered table-striped">
						<thead>
							<tr>
								<th width="20px">No.</th>
								<th>Jenis Biaya Kuliah</th>
								<th width="180px">Jumlah</th>
							</tr>
						</thead>
						<tbody>
							@foreach($jenis as $j)
							<tr>
								<td>{{ $c }}</td>
								<td>{{ $j -> nama }}</td>
								<td>
									<div class="input-group">
										<span class="input-group-addon">Rp </span>
										<input type="hidden" value="{{ $j -> id }}" name="jenis_biaya[]"/>
										<input type="text" value="{{ $j -> jumlah or 0 }}" name="jumlah[]" class="currency form-control biaya"/>
									</div>
								</td>
							</tr>
							<?php 
								$total += $j -> jumlah;
								$c++; 
							?>
							@endforeach
							<tr>
								<td colspan="2" align="right"><strong>TOTAL</strong></td>
								<td><strong>Rp <span style="display:inline-block; float:right;" id="total">{{ number_format($total, 0, ',', '.') }}</span></strong></td>
							</tr>
						</tbody>
					</table>
					<br/>
					<div style="position: relative">
						<button class="btn btn-flat btn-primary btn-submit" type="button"><i class="fa fa-floppy-o"></i> Simpan</button>
					</div>
					@endif
				</div>
			</div>		
		</div>
	</div>
</form>
@endsection																																																																																								
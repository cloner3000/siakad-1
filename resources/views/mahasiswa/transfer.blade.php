@extends('app')

@section('title')
Update Data Mahasiswa
@endsection

@section('header')
<section class="content-header">
	<h1>
		Mahasiswa
		<small>Update Data</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/mahasiswa') }}"> Daftar Mahasiswa</a></li>
		<li class="active">Update Data</li>
	</ol>
</section>
@endsection

@section('scripts')
<script type="text/javascript">
	var loader = '<i class="fa fa-hourglass-o fa-spin fa-2x loader"></i>';
	
	$(document).on('click', '.btn-in', function(){
		
		if($('select[name=\'kelas\'].select-to').val() == '-' && $('select[name=\'semester\'].select-to').val() == '-') return;
		if($('.from input:checkbox').length < 1) return;
		
		var n = 0;
		var id = {};
		var name = {};
		var clone = '';
		$('.from input:checked').each(function(){
			me = $(this);
			// div = me.closest('.checkbox');
			id[n] = me.val();
			// $(div).detach().appendTo('.to');
			n++;
		});
		$('input:checked').prop('checked', false);
		if(n > 0)
		{
			$.ajax({
				url: '{{ url("/mahasiswa/do/transfer") }}',
				type: "post",
				data: {
					'id' : id,
					'angkatan-from': $('select[name=\'kelas\'].select-from').val(),
					'kelas-from': $('select[name=\'kelas\'].select-from').val(),
					'semester-from': $('select[name=\'semester\'].select-from').val(),
					'kelas': $('select[name=\'kelas\'].select-to').val(),
					'semester': $('select[name=\'semester\'].select-to').val(),
					'_token': '{{ csrf_token() }}'
				},
				beforeSend: function()
				{
					$('.from').prepend(loader);
					$('.to').prepend(loader);
				},
				success: function(data){
					if(data.from.length > 0)
					{
						var list = '';
						for(c=0; c<data.from.length; c++ ) list += '<div class="checkbox"><label><input type="checkbox" value="'+ data.from[c]['id'] +'" ><span class="number">' + (c + 1) + '</span>. '+ data.from[c]['NIM'] +' - '+ data.from[c]['nama'] +'</label></div>';
						$('.from').empty().append(list);
					}
					if(data.to.length > 0)
					{
						var list = '';
						for(c=0; c<data.to.length; c++ ) list += '<div class="checkbox"><label><input type="checkbox" value="'+ data.to[c]['id'] +'" ><span class="number">' + (c + 1) + '</span>. '+ data.to[c]['NIM'] +' - '+ data.to[c]['nama'] +'</label></div>';
						$('.to').empty().append(list);
					}
					$('.loader').remove();
				}
			});  
		}
	});
	
	$(document).on('change', '.check-all', function (){
		var me = $(this);
		$("." + me.val() + " :checkbox").prop('checked', me.prop("checked"));
	});
	
	$(document).on('click', '.btn-filter', function (){
		var me = $(this);
		var target = me.attr('id').split('-')[1].trim();
		$.ajax({
			url: '{{ url("/mahasiswa/transfer") }}',
			type: "post",
			data: {
				'angkatan' : me.siblings('select[name=\'angkatan\']').length ? me.siblings('select[name=\'angkatan\']' ).val() : '-',
				'semester' : me.siblings('select[name=\'semester\']' ).val(),
				'kelas' : me.siblings('select[name=\'kelas\']' ).val(),
				'_token': '{{ csrf_token() }}'
			},
			beforeSend: function()
			{
				$('.' + target).prepend(loader);
			},
			success: function(data){
				$('.loader').remove();
				var list = '';
				for(c=0; c<data.mahasiswa.length; c++ ) list += '<div class="checkbox"><label><input type="checkbox" value="'+ data.mahasiswa[c]['id'] +'" ><span class="number">' + (c + 1) + '</span>. '+ data.mahasiswa[c]['NIM'] +' - '+ data.mahasiswa[c]['nama'] +'</label></div>';
				$('.' + target).empty().append(list);
			}
		});  
	});
</script>
@endsection

@section('styles')
<style>
	.form-group{
	margin-bottom: 0px;
	}
	label{
	text-align: left !important;
	}
	.daftar{
	margin-top: 10px;
	padding: 3px 0 3px 10px;
	border: 1px solid #ddd;
	border-radius: 5px;
	height: 300px;
	overflow-y: scroll;
	position:relative;
	}
	.loader{
	position: absolute;
	left: 50%;
	top: 30%;
	}
	select.form-control{
	display: inline-block;
	width: auto;
	}
	.btn-filter{
	margin-top: -3px;
	}
</style>
@endsection

@section('content')
<div class="box box-danger">
	<div class="box-body">
		<div class="row">
			<div class="col-sm-12">
				<div class="form-horizontal" role="form">
					<div class="row" style="margin-top: 3px;">
						<div class="col-md-5">
							<div style="margin-top: 5px;">
								{!! Form::select('angkatan', $angkatan, null, ['class' => 'select-from form-control']) !!}
								{!! Form::select('semester', $semester, null, ['class' => 'select-from form-control']) !!}
								{!! Form::select('kelas', $kelas, null, ['class' => 'select-from form-control']) !!}
								<button class="btn btn-success btn-filter btn-flat" id="btn-from"><i class="fa fa-filter"></i></button>
								<div class="checkbox">
									<label>
										<input type="checkbox" class="check-all" value="from" >
										Pilih semua
									</label>
								</div>
							</div>
							<div class="daftar from">
								<?php $c = 1; ?>
								@foreach($mahasiswa as $mhs)
								<div class="checkbox">
									<label>
										<input type="checkbox" value="{{ $mhs->id }}" >
										<span class="number">{{ $c }}</span>. {{ $mhs->NIM }} - {{ $mhs->nama }}
									</label>
								</div>
								<?php $c++; ?>
								@endforeach
							</div>
						</div>
						<div class="col-md-2" style="height: 400px; display: flex; align-items: center; justify-content: center;">
							<div style="width: 100%">
								<button class="btn btn-lg btn-primary btn-in btn-block btn-flat" type="button">>></button>
							</div>
						</div>
						<div class="col-md-5">
							<div style="margin-top: 5px;">
								{!! Form::select('semester', $semester, null, ['class' => 'select-to form-control']) !!}
								{!! Form::select('kelas', $kelas, null, ['class' => 'select-to form-control']) !!}
								<button class="btn btn-success btn-filter btn-flat" id="btn-to"><i class="fa fa-filter"></i></button>
								<div class="checkbox">
									<label>
										<input type="checkbox" class="check-all" value="to" >
										Pilih semua
									</label>
								</div>
							</div>
							<div class="daftar to">
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection																																																					
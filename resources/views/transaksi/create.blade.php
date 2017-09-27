@extends('app')

@section('title')
Transaksi Lain
@endsection

@section('content')
<h2>Transaksi Lain</h2>
{!! Form::model(new Siakad\Transaksi, ['class' => 'form-horizontal', 'role' => 'form', 'route' => ['transaksi.store']]) !!}
@include('transaksi._partials.form')
{!! Form::close() !!}
@endsection	
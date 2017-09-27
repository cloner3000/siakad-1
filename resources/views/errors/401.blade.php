@extends('app')

@section('header')
&nbsp;
@endsection

@section('content')
<div class="error-page">
	<h2 class="headline text-red"> 401</h2>
	
	<div class="error-content">
		<h3><i class="fa fa-warning text-red"></i> Oops! Unauthorized.</h3>
		
		<p>
			You dont have required access level to view this page.
			Meanwhile, you may <a href="/">return to dashboard</a> or try using the search form.
		</p>
		
		<form class="search-form">
			<div class="input-group">
				<input type="text" name="search" class="form-control" placeholder="Search">
				
				<div class="input-group-btn">
					<button type="submit" name="submit" class="btn btn-danger btn-flat"><i class="fa fa-search"></i>
					</button>
				</div>
			</div>
		</form>
	</div>
</div>
@endsection

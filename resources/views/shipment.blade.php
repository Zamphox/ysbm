@extends('index')
<title>Shipment [name]</title>
@section('content')
<div class="container">
	<h3 class="heading">Your Shipment: </h3>
	<div class="jumbotron">
		<h3 class="text-center" id="loading">Loading...</h3>
		<div id="shipment">
                <shipment></shipment>
		</div>
	</div>



</div>


@endsection
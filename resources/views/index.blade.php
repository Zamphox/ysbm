<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>YSBM Assignment</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<link rel="stylesheet" href="{{ URL::asset('css/custom.css') }}">

  </head>

  <body>
@section('navbar')
    <nav class="navbar fixed-top navbar-expand-lg">
      <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">YSBM Test</a>
        <div>
          <div class="navbar-nav">
              <a class="nav-item nav-link" href="{{ route('home') }}">Home</a>
              <a class="nav-item nav-link" href="#">About</a>
              <a class="nav-item nav-link" href="{{ route('logout') }}">Logout</a>
          </div>
        </div>
      </div>
    </nav>
@show

@section('content')

    <div class="container">
		<div class="row heading">
		  <h1 class="col-md-7">Welcome!
			<small> Your shipments: </small>
		  </h1>
			<form style="margin-top: -20px" class="col-md-5 form-row" action="/" method="post" enctype="multipart/form-data">
				@csrf
				<div class="col-md-11">
					<input placeholder="New Shipment!" id="name" name="name" type="text" value="{{ old('name') }}" class="form-control input {{ $errors->has('email') ? ' is-invalid' : '' }}">
				@if ($errors->has('name'))
					<span class="invalid-feedback text-center">
						<strong>{{ $errors->first('name') }}</strong>
					</span>
				@endif
				</div>
				<div class="text-center col-md-1">
					<button style="margin-top: -32px" type="submit" class="btn btn-custom">
						Add
					</button>
				</div>
			</form>
		</div>
		@if(session('success'))
          <div class='alert alert-success col-md-8 offset-md-2 text-center'>
            {{ session('success') }}
          </div>
        @endif

		@if(session('error'))
          <div class='alert alert-danger col-md-8 offset-md-2 text-center'>
            {{ session('error') }}
          </div>
        @endif

      <div class="row">
		  @if(isset($data))
		  @foreach($data as $shipment)
		  <div class="col-lg-4 col-sm-6 portfolio-item shipment_item">
		<a href="/shipment/{{ $shipment['id'] }}">
          	<div class="card h-100">
				<img class="card-img-top" src="{{ $shipment['is_send'] ? asset('OrderSent.jpg') : asset('OrderNotSent.jpg') }}" alt="">
				<div class="text-center card-body">
				  <h4 class="card-title">
					<h3 class="shipment_name">{{ $shipment['name'] }}</h3>
				  </h4>
					@if(empty($shipment['items']))
						<h4 class="card-info">No items yet!</h4>
					@endif
				  <ul class="card-text list-group">
					@foreach(array_slice($shipment['items'], 0, 5, true) as $item)
					  <li class="list-row">{{$item['name']}}</li>
					@endforeach
					</ul>
					</a>
				</div>
          </div>
        </div>
	  @endforeach
		  @endif
    </div>
		@if(isset($data))
		<span class="d-flex justify-content-center links">{{ $data->links() }}</span>
		@endif
	  </div>

@show



@section('footer')
    <footer>

		</footer>
@show

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
	<script type="text/javascript" src="{{asset('js/app.js')}}"></script>
	<script src="{{asset('js/lodash.js')}}"></script>

  </body>

</html>
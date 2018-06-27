@extends('index')
<title>Shipment [name]</title>
@section('content')
<div class="container">
	<h1 class="heading text-center">You have to be logged in
        <small> to use our service </small>
      </h1>

	@if(session('error'))
          <div class='alert alert-danger col-md-8 offset-md-2 text-center'>
            {{ session('error') }}
          </div>
        @endif

	<div>

	</div>
	<div class="well well-sm col-md-8 offset-md-2">
          <form class="form-horizontal jumbotron" action="/login" method="post" enctype="multipart/form-data">
          	@csrf
          <fieldset>
            <div class="form-group row">
              <label class="col-md-12 text-center control-label" for="email" value="{{ old('name') }}">Email:</label>
				<input id="email" name="email" value="{{ old('email') }}" type="text" placeholder="Email@mail.com" class="offset-md-3 col-md-6 form-control input-md {{ $errors->has('email') ? ' is-invalid' : '' }}">
				@if ($errors->has('email'))
                                    <span class="invalid-feedback text-center">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                           @endif
			  <label class="col-md-12 text-center control-label" for="password" value="{{ old('name') }}">Password:</label>
				<input id="password" name="password" value="{{ old('password') }}" type="password" placeholder="Password" class="offset-md-3 col-md-6 form-control input-md {{ $errors->has('password') ? ' is-invalid' : '' }}">
				@if ($errors->has('password'))
                                    <span class="invalid-feedback text-center">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif


            </div>



            <div class="form-group">
              <div class="text-right">
                <button type="submit" class="btn btn-custom btn-lg">Login</button>
              </div>
            </div>
          </fieldset>
          </form>
        </div>
</div>
</div>
</div>
</div>

@endsection
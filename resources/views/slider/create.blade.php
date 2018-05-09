@extends('layouts.app')

@section('title', trans('slider.add_slide'))

@section('content')

<div class="container pb-5">
	<h4 class="display-4 text-center p-3">@lang('slider.add_slide')</h4>
	<div>
		<img src="{{ asset('/storage/img/slider/default.jpg') }}" class="rounded mx-auto d-block" id="target-image">
	</div>
	<div class="col-md-8 offset-md-2">

		<form action="{{ action('SliderController@store') }}" method="post" enctype="multipart/form-data" class="row">
			@csrf
			<div class="custom-file mt-3 col-12 col-md-6">
				<input type="file" name="image" class="custom-file-input" id="src-image" required>
				<label class="custom-file-label" for="src-image">
					@lang('forms.choose_file')...
				</label>
			</div>
			<div class="form-group mt-3 col-12 col-md-6">
				<input type="number" class="form-control" name="order" placeholder="@lang('slider.choose_order_number')">
			</div>

			<button type="submit" class="btn btn-pink mt-3">@lang('slider.add_slide')</button>
		</form>

	</div>
</div>

@endsection
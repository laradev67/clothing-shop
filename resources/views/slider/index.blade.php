@extends('layouts.app')

@section('title', trans('slider.slider'))

@section('content')

<div class="container">
	
	@if ($slider->count() > 0)
		<h3 class="display-4 text-center pt-3 pb-3">@lang('slider.slider')</h3>

		<table class="table">
			<thead class="thead-light">
				<tr>
					<th scope="col">@lang('slider.image')</th>
					<th scope="col">@lang('slider.order')</th>
					<th scope="col"></th>
				</tr>
			</thead>
			<tbody>
				@foreach ($slider->reverse() as $slide)
					<tr>
						<td><img src="{{ asset('storage/img/slider/' . $slide->image) }}" style="max-width:100px"></td>
						<th scope="row">{{ $slide->order }}</th>
						<td>
							<a href="slider/{{ $slide->id }}/edit" class="btn btn-dark" title="@lang('slider.edit')">
								<i class="fa fa-pencil" aria-hidden="true"></i>
							</a>
							<form action="{{ action('SliderController@destroy', ['slider' => $slide->id]) }}" method="post" onsubmit='return confirm("@lang('slider.are_you_sure')")' class="d-inline">

								@csrf @method('delete')

								<button type="submit" class="btn btn-danger" title="@lang('slider.delete')">
									<i class="fa fa-trash-o" aria-hidden="true"></i>
								</button>
							</form>
						</td>
					</tr>	
				@endforeach
			</tbody>
		</table>
	@endif
	<div class="text-center pb-5">
		<div class="alert alert-light mb-3" role="alert">
			@lang('slider.amount_of_slides'): {{ $slider->count() }}
		</div>
		<a href="/slider/create" title="@lang('slider.add_slide')" class="btn btn-dark">@lang('slider.add_slide')</a>
	</div>
</div>

@endsection
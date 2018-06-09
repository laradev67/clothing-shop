@extends('layouts.app')

@section('title', trans('user-sidebar.work'))

@section('content')
<div class="container">
	<h4 class="text-center p-3">@lang('dashboard.your_orders'):</h4>
	<clients-orders
		admin="{{ user()->admin }}"
		sum="@lang('dashboard.sum')"
		date="@lang('dashboard.date')"
		number="@lang('dashboard.number')"
		product="@lang('dashboard.product')"
		no-orders="@lang('dashboard.no_orders')"
		delete-number="@lang('dashboard.delete_number')"
		delete-this-order="@lang('dashboard.delete_this_order')">
	</clients-orders>
</div>
@endsection

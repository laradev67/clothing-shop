<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>@include('includes.head')</head>
<body>
	<main id="app">
		<div class="loading" id="loading"></div>
		<span class="loading-title" id="loading-title">Загрузка ...</span>
		@include('includes.user-sidebar')
		@include('includes.nav')
		@include('includes.gsm')
		@include('includes.messages')
		@yield('content')
		@include('includes.footer')
	</main>
	@include('includes.script')
</body>
</html>
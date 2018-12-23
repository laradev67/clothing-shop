@extends('layouts.app')

@section('title', trans('cart.cart'))

@section('content')

<div class="container">
    @if (Cart::count() > 0)
        <h4 class="text-center pt-4 pb-3 font-weight-normal">
            @lang('cart.cart')
        </h4>
        <table class="table table-hover table-condensed">
            <thead>
                <tr>
                    <th style="width:50%">@lang('cart.product')</th>
                    <th style="width:20%" class="text-center">
                        @lang('cart.price')
                    </th>
                    <th style="width:30%"></th>
                </tr>
            </thead>
            <tbody>
                @foreach (Cart::content() as $item)
                    <tr>
                        <td>
                            <div class="row">
                                <a href="/item/{{ $item->model->category }}/{{ $item->model->id }}"
                                    class="col-12 col-sm-2"
                                >
                                    <img src="{{ asset("storage/img/clothes/{$item->model->photos[0]->name}") }}"
                                        alt="{{ $item->model->title }}"
                                        class="img-responsive" 
                                        style="max-width:50px;"
                                    />
                                </a>
                                <div class="col-12 col-sm-10 pt-2">
                                    <h6 class="nomargin">{{ $item->model->title }}</h6>
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            <h6>{{ $item->model->price }} @lang('items.hryvnia')</h6>
                        </td>
                        <td class="text-center">
                            <form action="{{ action('CartController@addToFavorite', ['id' => $item->rowId]) }}" 
                                method="post" 
                                class="d-inline"
                            >
                                @csrf
                                <button type="submit"
                                    class="btn btn-dark btn-sm" 
                                    title="@lang('cart.add_to_favorite')"
                                >
                                    <i class="fas fa-star"></i>
                                </button>
                            </form>
                            <form action="{{ action('CartController@destroy', ['item' => $item->rowId]) }}" 
                                method="post" 
                                class="d-inline"
                            >
                                @csrf @method('delete')

                                <button type="submit"
                                    class="btn btn-danger btn-sm" 
                                    title="@lang('cart.delete')"
                                >
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>

            {{-- Table Footer --}}
            <tfoot>
                <tr>
                    <td>
                        <button-back title="@lang('messages.back')"></button-back>
                    </td>
                    <td class="hidden-xs text-center">
                        <strong>
                            @lang('cart.total')  {{ Cart::total() }} @lang('items.hryvnia')
                        </strong>
                    </td>
                    <td>
                        <a href="/checkout" class="btn btn-success btn-block">
                            @lang('cart.order') <i class="fas fa-angle-right"></i>
                        </a>
                    </td>
                </tr>
            </tfoot>
        </table>
    @else
        <p class="text-center pt-5">@lang('cart.empty')</p>
    @endif

    {{-- Favorite --}}
    @if (Cart::instance('favorite')->count() > 0)
        <h5 class="text-center pt-2 font-weight-normal">
            @lang('cart.favorite')
        </h5>
    @endif

    <section class="row pt-4">
        @if (Cart::instance('favorite')->count() > 0)
            <table id="cart" class="table table-hover table-condensed">
                <thead>
                    <tr>
                        <th style="width:50%">@lang('cart.product')</th>
                        <th style="width:20%" class="text-center">
                            @lang('cart.price')
                        </th>
                        <th style="width:30%"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach (Cart::instance('favorite')->content() as $item)
                        <tr>
                            <td>
                                <div class="row">
                                    <a href="/item/{{ $item->model->category }}/{{ $item->model->id }}" 
                                        class="col-12 col-sm-2"
                                    >
                                        <img src="{{ asset("storage/img/clothes/{$item->model->photos[0]->name}") }}" 
                                            alt="{{ $item->model->title }}" 
                                            class="img-responsive" 
                                            style="max-width:50px;"
                                        />
                                    </a>
                                    <div class="col-12 col-sm-10 pt-2">
                                        <h6 class="nomargin">{{ $item->model->title }}</h6>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                <h6>{{ $item->model->price }} @lang('items.hryvnia')</h6>
                            </td>
                            <td class="text-center">
                                <form class="d-inline" 
                                    action="{{ action('FavoriteItemController@addToCart', ['id' => $item->rowId]) }}" 
                                    method="post"
                                >
                                    @csrf

                                    <button type="submit"
                                        class="btn btn-dark btn-sm" 
                                        title="@lang('cart.add_to_cart')"
                                    >
                                        <i class="fas fa-cart-plus"></i>
                                    </button>
                                </form>
                                <form class="d-inline" 
                                    action="{{ action('FavoriteItemController@destroy', ['id' => $item->rowId]) }}" 
                                    method="post"
                                >
                                    @csrf @method('delete')

                                    <button type="submit"
                                        class="btn btn-danger btn-sm" 
                                        title="@lang('cart.delete')"
                                    >
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </section>
</div>

@endsection
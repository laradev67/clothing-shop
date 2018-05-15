<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use App\Http\Requests\CheckoutRequest;
use Gloudemans\Shoppingcart\Facades\Cart;

class CheckoutController extends Controller
{
    // Display a listing of the resource
    public function index()
    {
		return (Cart::instance('default')->count() > 0)
			? view('checkout.index')
			: redirect('/cart');
    }

    // Store a newly created resource in storage
    public function store(CheckoutRequest $request)
    {
		if (Message::whereIp($request->ip())->count() > 0) {
			return back()->withError(trans('checkout.olready_did_order'));
		}

		try {
			$data = '{';
			foreach (Cart::content() as $item) {
				$data .= ' || ' . $item->id;
			}

			$send = Message::create([
				'ip' => $request->ip(),
				'phone' => $request->phone,
				'name' => $request->name,
				'total' => str_replace(' ', '', Cart::total()),
				'order' => $data,
			]);

				Cart::instance('default')->destroy();
				return redirect('/cart')->withSuccess(trans('checkout.order_sent'));
		} catch (Exception $e) {
			return back()->withError(
				trans('checkout.error') . ' ' . $e->getMessage()
			);
		}
		
    }
}

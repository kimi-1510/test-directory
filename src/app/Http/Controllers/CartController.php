<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = CartItem::firstOrNew([
            'session_id' => Session::getId(),
            'product_id' => $request->product_id,
        ]);

        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        return response()->json(['message' => 'カートに追加しました']);
    }

    public function show()
    {
        $cart = Session::get('cart', []);
        $total = 0;

        // 合計金額を計算
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('cart.show', [
            'cart' => $cart,
            'total' => $total
        ]);
    }

    public function remove($productId)
    {
        $cart = Session::get('cart', []);

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            Session::put('cart', $cart);
        }

        return redirect()->back()->with('success', '商品をカートから削除しました');
    }

    public function update(Request $request, $productId)
    {
        $quantity = $request->input('quantity');
        $cart = Session::get('cart', []);

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] = $quantity;
            Session::put('cart', $cart);
        }

        return redirect()->back()->with('success', 'カートを更新しました');
    }
} 
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        // 仮の商品データ
        $products = [
            [
                'id' => 1,
                'name' => '商品1',
                'price' => 1000,
                'description' => '商品1の説明文です。'
            ],
            [
                'id' => 2,
                'name' => '商品2',
                'price' => 2000,
                'description' => '商品2の説明文です。'
            ],
            [
                'id' => 3,
                'name' => '商品3',
                'price' => 3000,
                'description' => '商品3の説明文です。'
            ],
        ];

        return view('index', compact('products'));
    }
} 
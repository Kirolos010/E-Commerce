<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    /**
     * Add a product to the cart.
     */
    public function addToCart(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity', 1);

        // Retrieve product details
        $product = Product::findOrFail($productId);
        $currentPrices = $this->getCurrentPrices(); // Fetch current prices

        // Check if the product has a current price
        $priceRecord = $currentPrices->firstWhere('id', $productId);
        $price = $priceRecord ? $priceRecord->price : null;

        if (!$price) {
            return redirect()->route('products.index')->with('error', 'Product price not available.');
        }

        // Get the cart from session or initialize it if it doesn't exist
        $cart = Session::get('cart', []);

        // If the product is already in the cart, update quantity
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
        } else {
            // Add product to cart
            $cart[$productId] = [
                'name' => $product->name,
                'quantity' => $quantity,
                'price' => $price,
            ];
        }

        // Save cart back to session
        Session::put('cart', $cart);

        return redirect()->route('cart.view')->with('success', 'Product added to cart!');
    }

    /**
     * Get current prices for all products.
     */
    public function getCurrentPrices()
    {
        $currentDate = now();
        return DB::table('prices')
            ->join('products', 'prices.product_id', '=', 'products.id')
            ->where('prices.start_date', '<=', $currentDate)
            ->where('prices.end_date', '>=', $currentDate)
            ->select('products.id', 'prices.price')
            ->get();
    }

    /**
     * Display the cart.
     */
    public function viewCart()
    {
        $cart = Session::get('cart', []);
        return view('cart.view', compact('cart'));
    }

    // Optionally, you can add a method to remove items from the cart
    public function removeFromCart($productId)
    {
        $cart = Session::get('cart', []);

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            Session::put('cart', $cart);
            return redirect()->route('cart.view')->with('success', 'Product removed from cart!');
        }

        return redirect()->route('cart.view')->with('error', 'Product not found in cart.');
    }
}

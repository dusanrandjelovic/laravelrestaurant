<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductsController extends Controller
{
    public function index()
    {
       // $products = Product::all();
       
         $products = new Product;

          if(request()->has('category')){
          $products = $products->where('category', request('category'));
          }

          if(request()->has('sort')){
            $products = $products->orderBy('price', request('sort'));
            }

            $products = $products->paginate(8)->appends([
              'category' => request('category'),
              'sort' => request('sort'),
            ]);

        return view('products', compact('products'));
    }
    
     public function search(Request $request){
          $search = $request->get('search');
          $products = DB::table('products')->where('description', 'like', '%' .$search. '%')->paginate(8);
          return view('products', compact('products'));
        }
    
     public function show($id)
    {
        $product = Product::find($id);
        return view('products.show', compact('product'));
    }

    public function cart()
    {
        return view('cart');
    }
     public function checkout()
    {
        return view('checkout');
    }
    
    public function addToCart($id)
    {
        $product = Product::find($id);

        if(!$product) {

            abort(404);

        }

        $cart = session()->get('cart');

        // if cart is empty then this the first product
        if(!$cart) {

            $cart = [
                $id => [
                    "name" => $product->name,
                    "quantity" => 1,
                    "price" => $product->price,
                    "photo" => $product->photo
                ]
            ];

            session()->put('cart', $cart);

            $htmlCart = view('_header_cart')->render();

            return response()->json(['msg' => 'Product added to cart successfully!', 'data' => $htmlCart]);

            //return redirect()->back()->with('success', 'Product added to cart successfully!');
        }

        // if cart not empty then check if this product exist then increment quantity
        if(isset($cart[$id])) {

            $cart[$id]['quantity']++;

            session()->put('cart', $cart);

            $htmlCart = view('_header_cart')->render();

            return response()->json(['msg' => 'Sajt je dodat u korpu!', 'data' => $htmlCart]);

            //return redirect()->back()->with('success', 'Product added to cart successfully!');

        }

        // if item not exist in cart then add to cart with quantity = 1
        $cart[$id] = [
            "name" => $product->name,
            "quantity" => 1,
            "price" => $product->price,
            "photo" => $product->photo
        ];

        session()->put('cart', $cart);

        $htmlCart = view('_header_cart')->render();

        return response()->json(['msg' => 'Sajt je dodat u korpu!', 'data' => $htmlCart]);

        //return redirect()->back()->with('success', 'Product added to cart successfully!');
    }

    public function update(Request $request)
    {
        if($request->id and $request->quantity)
        {
            $cart = session()->get('cart');

            $cart[$request->id]["quantity"] = $request->quantity;

            session()->put('cart', $cart);

            $subTotal = $cart[$request->id]['quantity'] * $cart[$request->id]['price'];

            $total = $this->getCartTotal();

            $htmlCart = view('_header_cart')->render();

            return response()->json(['msg' => 'Stanje u korpi ažurirano', 'data' => $htmlCart, 'total' => $total, 'subTotal' => $subTotal]);

            //session()->flash('success', 'Cart updated successfully');
        }
    }

    public function remove(Request $request)
    {
        if($request->id) {

            $cart = session()->get('cart');

            if(isset($cart[$request->id])) {

                unset($cart[$request->id]);

                session()->put('cart', $cart);
            }

            $total = $this->getCartTotal();

            $htmlCart = view('_header_cart')->render();

            return response()->json(['msg' => 'Uspešno obrisano', 'data' => $htmlCart, 'total' => $total]);

            //session()->flash('success', 'Product removed successfully');
        }
    }


    /**
     * getCartTotal
     *
     *
     * @return float|int
     */
    private function getCartTotal()
    {
        $total = 0;

        $cart = session()->get('cart');

        foreach($cart as $id => $details) {
            $total += $details['price'] * $details['quantity'];
        }

        return number_format($total, 2);
    }
}

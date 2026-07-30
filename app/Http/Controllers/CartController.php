<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;
use Cart;

class CartController extends Controller {

    public function getIndex() {
        // Cart::destroy();
        $data = Cart::content();
        parent::$data['info'] = Cart::content();
        parent::$data['priceTotal'] = Cart::priceTotal();
//        parent::$data['cart_count'] = Cart::count();
        return view('frontend.cart.view', parent::$data);
        //return Cart::content();
    }

    ///////////////////////////////////////
    public function getAdd($id) {
        $products = new Products();
        $product = $products->getProduct($id);
        if ($product) {
            //$add = Cart::add($product->id, $product->title, 1, $product->price, 0, ['image' => $product->image]);
//            $add = Cart::add($product->id, $product->title, 1, $product->price, 0);
            //if ($add) {
            parent::$data['info'] = $product;
            echo view('frontend.cart.cart_modal', parent::$data);
            //}
        } else {
            return response()->view('errors.404', parent::$data, 500);
        }
    }

    public function postAdd(Request $request, $id) {
        $products = new Products();
        $product = $products->getProduct($id);
        if ($product) {
            $quantity = $request->get('quantity');
            $add = Cart::add($product->id, $product->title, $quantity, $product->price * $quantity, 0, ['image' => $product->image, 'checkout' => $product->checkout]);
            if ($add) {
                $total = Cart::count();
                return response()->json(['status' => 'success', 'message' => 'تمت الاضافة', 'type' => 'yes', 'items' => $total]);
            }
        } else {
            return response()->json(['status' => 'error', 'message' => 'حدثت مشكلة الرجاء المحاولة مة اخري', 'type' => 'no']);
        }
    }

    public function postBuy(Request $request, $id) {
        $products = new Products();
        $product = $products->getProduct($id);
        $items = Cart::content();
        $total = Cart::count();
        $quantity = $request->get('quantity');
        //$url = "https://secure.2checkout.com/checkout/buy?merchant=494135&style=one-column5ed7b52947dd7&tpl=one-column";
        $url = 'https://www.2checkout.com/checkout/purchase?sid=494135';
        if ($product) {
            $i = 0;
            https://www.2checkout.com/checkout/purchase?sid=494135&product_id31=31&product_id32=32&=3&quantity32=2
            $temp_pro = '&product_id' . $product->checkout . '=' . $product->checkout;
            $temp_qty = '&quantity' . $product->checkout . '=' . $quantity;
            $pr = '&prod=' . $product->checkout . ($total > 0 ? '%3B' : '');
            $qt = '&qty=' . $quantity . ($total > 0 ? '%3B' : '');
            foreach ($items as $item) {
                $i++;
                $temp_pro .= '&product_id' . $item->options['checkout'] . '=' . $item->options['checkout'];
                $temp_qty .= '&quantity' . $item->options['checkout'] . '=' . $item->qty;
                $pr .= $item->options['checkout'] . ($i != $total ? '%3B' : '');
                $qt .= $item->qty . ($i != $total ? '%3B' : '');
            }
            $this->postEmpty();
            //return redirect($url . $pr . $qt);
            return redirect($url . $temp_pro . $temp_qty);
        } elseif ($total > 0) {
            $i = 0;
            $temp_pro = '';
            $temp_qty = '';

            $pr = '&prod=';
            $qt = '&qty=';
            foreach ($items as $item) {
                $i++;
                $temp_pro .= '&product_id' . $item->options['checkout'] . '=' . $item->options['checkout'];
                $temp_qty .= '&quantity' . $item->options['checkout'] . '=' . $quantity[$item->id];
                $pr .= $item->options['checkout'] . ($i != $total ? '%3B' : '');
                $qt .= $quantity[$item->id] . ($i != $total ? '%3B' : '');
            }

//            echo $url . $temp_pro . $temp_qty;
//            exit;
            $this->postEmpty();
//            return redirect($url . $pr . $qt);
            return redirect($url . $temp_pro . $temp_qty);
        } else {
            $request->session()->flash('danger', 'حدثت مشكلة الرجاء المحاولة مة اخري');
            return redirect(route('products.allProducts'));
        }
    }

    public function postDelete(Request $request, $id) {
        if ($id) {
            Cart::remove($id);
            return response()->json(['status' => 'error', 'message' => 'تم الحذف بنجاح', 'type' => 'yes']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'حدثت مشكلة الرجاء المحاولة مة اخري', 'type' => 'no']);
        }
    }

    ///////////////////////////////////////
    public function postEmpty() {
        $cart = Cart::content();
        foreach ($cart as $row) {
            Cart::remove($row->rowId);
        }
    }

    ///////////////////////////////////////
}

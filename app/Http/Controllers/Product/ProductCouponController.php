<?php

namespace App\Http\Controllers\Product;

use App\Helpers\FlashMsg;
use App\Http\Controllers\Controller;

use App\Language;
use App\ProductCoupon;
use Illuminate\Http\Request;

class ProductCouponController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function all_coupon(){
        $all_product_coupon = ProductCoupon::all();
        return view('backend.products.coupon.all-coupon')->with(['all_product_coupon' => $all_product_coupon]);
    }

    public function store_coupon(Request $request){
        $this->validate($request,[
            'code' => 'required|string|max:191|unique:product_coupons',
            'discount' => 'required|string|max:191',
            'discount_type' => 'required|string|max:191',
            'expire_date' => 'required|string|max:191',
            'status' => 'required|string|max:191',
        ]);

        ProductCoupon::create($request->all());

        return redirect()->back()->with(FlashMsg::item_new());
    }

    public function update_coupon(Request $request){
        $this->validate($request,[
            'code' => 'required|string|max:191|unique:product_coupons,code,'.$request->id,
            'discount' => 'required|string|max:191',
            'discount_type' => 'required|string|max:191',
            'expire_date' => 'required|string|max:191',
            'status' => 'required|string|max:191',
        ]);

        ProductCoupon::find($request->id)->update($request->all());

        return redirect()->back()->with(FlashMsg::item_update());
    }

    public function delete_coupon(Request $request,$id){
        ProductCoupon::find($id)->delete();
        return redirect()->back()->with(FlashMsg::item_delete());
    }

    public function bulk_action(Request $request){
        $all = ProductCoupon::find($request->ids);
        foreach($all as $item){
            $item->delete();
        }
        return response()->json(['status' => 'ok']);
    }
}

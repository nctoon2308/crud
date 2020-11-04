<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Backend\OrderDetailModel;
use App\Models\Backend\OrderModel;
use App\Models\Backend\ProductModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    //
    public function index (Request $request){

        $sort = $request->query('sort',"");
        $searchKeyword = $request->query('name',"");
        $queryORM = OrderModel::where('customer_name', "LIKE", "%".$searchKeyword."%");
        if ($sort=="name_asc"){
            $queryORM->orderBy('customer_name','asc');
        }
        if ($sort=="name_desc"){
            $queryORM->orderBy('customer_name','desc');
        }
        $orders = $queryORM->paginate(10);


        //$orders = DB::table('orders')->paginate(10);

        $data[]='';
        $data['orders']=$orders;

        $data["searchKeyword"] = $searchKeyword;
        $data["sort"] = $sort;

        $order_status_defined = [];
        $order_status_defined[1] = "Đang chờ xác nhận";
        $order_status_defined[2] = "Đã chờ xác nhận";
        $order_status_defined[3] = "Đang vận chuyển";
        $order_status_defined[4] = "Hoàn tất";
        $order_status_defined[5] = "Đơn huỷ";
        $order_status_defined[6] = "Đã hoàn tiền  (huỷ đơn)";
        $data["order_status_defined"] = $order_status_defined;

        return view("backend.orders.index",$data);
    }

    public function create(){
        return view("backend.orders.create");
    }

    public function searchProduct(Request $request) {
        $searchKeyword = $request->input("search", "");
        //$queryORM = ProductsModel::where('product_name', "LIKE", "%".$searchKeyword."%");
        $queryORM = DB::table('products')->where('product_name', "LIKE", "%".$searchKeyword."%");
        $products = $queryORM->paginate(50);
        $msg["results"] = [];
        if ($products) {
            foreach ($products as $product) {
                $msg["results"][] = ["id" => $product->id, "text" => $product->id . " - " . $product->product_name];
            }
        }

        //$product = ProductModel::findOrFail($id);

        return response()->json($msg, 200);
        //return 1;
    }

    public function ajaxSingleProduct(Request $request) {
        $id = (int) $request->input("id", "");
        $product = ProductModel::findOrFail($id);
        $productShort = [
            "id" => $product->id,
            "product_name" => $product->product_name,
            "product_image" => $product->product_image,
            "product_quantity" => $product->product_quantity,
            "product_price" => $product->product_price,
        ];
        $productShort["product_image"] = str_replace("public/", "", $productShort["product_image"]);
        $productShort["product_image"] = asset('storage')."/".$productShort["product_image"];
        return response()->json($productShort, 200);
    }



    public function store(Request $request) {
        echo "<pre>";
        print_r($_POST);
        echo "</pre>";
        // validate dữ liệu
        $validatedData = $request->validate([
            'customer_name' => 'required',
            'customer_email' => 'required',
            'customer_phone' => 'required',
            'customer_address' => 'required',
            'order_status' => 'required',
            'order_note' => 'required',
        ]);
        if (!empty($product_ids) || !empty($product_quatities)) {
            return redirect("/backend/orders/create")->withInput()->withErrors(["product_ids" => "chưa chọn sản phẩm cho đơn hàng"]);
        }
        $customer_name = $request->input('customer_name', '');
        $customer_email = $request->input('customer_email', '');
        $customer_phone = $request->input('customer_phone', '');
        $customer_address = $request->input('customer_address', '');
        $order_status = $request->input('order_status', '');
        $order_note = $request->input('order_note', '');
        $product_ids = $request->input('product_ids');
        $product_quatities = $request->input('product_quatity');

        $order = new OrderModel();

        $order->customer_name = $customer_name;
        $order->customer_email = $customer_email;
        $order->customer_phone = $customer_phone;
        $order->customer_address = $customer_address;
        $order->order_status = $order_status;
        $order->order_note = $order_note;
        // thêm chi tiết đơn hàng
            foreach ($product_ids as $product_ids_key => $productId) {
                $quantity = $product_quatities[$product_ids_key];
                $product = ProductModel::find($productId);
                $totalPriceProduct = $quantity * $product->product_price;
                $order->total_product += $quantity;
                $order->total_price += $totalPriceProduct;
            }

        // lưu đơn hàng
        $order->save();
        // thêm chi tiết đơn hàng
        foreach($product_ids as $product_ids_key => $productId) {
            $quantity = $product_quatities[$product_ids_key];
            $product = ProductModel::find($productId);
            $totalPriceProduct = $quantity*$product->product_price;
            $orderDetail = new OrderDetailModel();
            $orderDetail->product_id = $productId;
            $orderDetail->product_price = $product->product_price;
            $orderDetail->quantity = $quantity;
            $orderDetail->order_id = $order->id;
            $orderDetail->order_status = $order_status;
            $orderDetail->save();
        }
        return redirect("/backend/orders/index")->with('status', 'thêm đơn hàng thành công !');
    }

    public function edit2($id){
        $order = OrderModel::findOrFail($id);

        $productInOrders = DB::table('products')
            ->join('orderdetail','orderdetail.product_id','=','products.id')
            ->select('products.id','products.product_name','products.product_image','orderdetail.order_id','orderdetail.quantity', 'orderdetail.product_price')
            ->where('orderdetail.order_id','=',$id)
            ->get();

        $data = [];
        $data['order'] = $order;
        $data["productInOrders"] = $productInOrders;

        return view("backend.orders.edit2",$data);
    }



    public function update(Request $request, $id){
        echo "<pre>";
        print_r($_POST);
        echo "</pre>";
        // validate dữ liệu
        $validatedData = $request->validate([
            'customer_name' => 'required',
            'customer_email' => 'required',
            'customer_phone' => 'required',
            'customer_address' => 'required',
            'order_status' => 'required',
            'order_note' => 'required',
        ]);

        $customer_name = $request->input('customer_name', '');
        $customer_email = $request->input('customer_email', '');
        $customer_phone = $request->input('customer_phone', '');
        $customer_address = $request->input('customer_address', '');
        $order_status = $request->input('order_status', '');
        $order_note = $request->input('order_note', '');

        $order = OrderModel::findOrFail($id);

        $order->customer_name = $customer_name;
        $order->customer_email = $customer_email;
        $order->customer_phone = $customer_phone;
        $order->customer_address = $customer_address;
        $order->order_status = $order_status;
        $order->order_note = $order_note;

        //lưu đơn hàng
        $order->save();

        $orderDetails = DB::table('orderdetail')->where("order_id", $order->id)->get();

        foreach($orderDetails as $orderDetail) {
            $orderDetail = OrderDetailModel::findOrFail($orderDetail->id);
            $orderDetail->order_status = $order_status;
            $orderDetail->save();
        }
        return redirect("/backend/orders/edit2/$id")->with('status', 'cập nhật đơn hàng thành công !');
    }

    public function delete ($id){
        $order = OrderModel::findOrFail($id);

        $data = [];
        $data['order'] = $order;
        return view("backend.orders/delete",$data);
    }

    // xóa đơn hàng thật sự trong CSDL

    public function destroy($id) {
        $order = OrderModel::findOrFail($id);
        $order->delete();
        DB::table('orderdetail')->where('order_id', '=', $id)->delete();
        return redirect("/backend/orders/index")->with('status', 'xóa đơn hàng thành công !');
    }


}

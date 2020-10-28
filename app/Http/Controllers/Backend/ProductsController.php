<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Backend\ProductModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductsController extends Controller
{
    //

    public function index(){
        //$products = ProductModel::all();
        $products = DB::table('products')->paginate(10);
        //truyen du lieu xuong view
        $data = [];
        $data["products"] = $products;
        return view("backend.products.index",$data);
    }

    public function create(){
        $data = [];
        $categories = DB::table('category')->get();
        $data["categories"] = $categories;

        return view("backend.products.create", $data);
    }

    public function delete($id){
        $product = ProductModel::findOrFail($id);

        $data = [];
        $data["product"] = $product;
        return view("backend.products.delete",$data);
    }

    public function edit2($id){
        $product = ProductModel::findOrFail($id);

        $data = [];
        $categories = DB::table('category')->get();
        $data["categories"] = $categories;
        $data["product"] = $product;

        return view("backend.products.edit2",$data);
    }


    public function store(Request $request){

        //validate
        $validatedData = $request->validate([
            'product_name' => 'required',
            'category_id' => 'required',
            'product_image' => 'required',
            'product_publish' => 'required',
            'product_desc' => 'required',
            'product_quantity' => 'required',
            'product_price' => 'required',
        ]);

        $product_name = $request->input('product_name','');
        $category_id = $request->input('category_id','');
        $product_status = $request->input('product_status',1);
        $product_desc = $request->input('product_desc','');
        $product_publish = $request->input('product_publish','');
        $product_quantity = $request->input('product_quantity',0);
        $product_price = $request->input('product_price',0);
        $pathProductImage = $request->file('product_image')->store('public/productimages');



        $product = new ProductModel();

        //nếu chưa có $product_publish thì $product_publish sẽ được gán thời gian hiện tại
        if (!$product_publish){
            $product_publish = date("Y-m-d H:i:s");
        }

        //gán dữ liệu từ request cho các thuộc tính của $product
        //$product là đối tượng khởi tạo từ model productModel
        $product->product_name = $product_name;
        $product->category_id = $category_id;
        $product->product_status = $product_status;
        $product->product_desc = $product_desc;
        $product->product_publish = $product_publish;
        $product->product_quantity = $product_quantity;
        $product->product_price = $product_price;
        //image tạm thời sẽ rỗng
        $product->product_image = $pathProductImage;

        $product->save();

        //chuyển hướng
        return redirect("/backend/product/index")->with('status','Thêm sản phẩm thành công');
    }

    public function update(Request $request, $id){

        $validatedData = $request->validate([
            'product_name' => 'required',
            'category_id' => 'required',
            'product_publish' => 'required',
            'product_desc' => 'required',
            'product_quantity' => 'required',
            'product_price' => 'required',
        ]);

        echo "<br>id: ".$id;
        echo "<pre>";
        print_r($_POST);
        echo "</pre>";

        $product_name = $request->input('product_name','');
        $category_id = $request->input('category_id','');
        $product_status = $request->input('product_status','');
        $product_desc = $request->input('product_desc','');
        $product_publish = $request->input('product_publish','');
        $product_quantity = $request->input('product_quantity',0);
        $product_price = $request->input('product_price',0);

        //nếu chưa có $product_publish thì $product_publish sẽ được gán thời gian hiện tại
        if (!$product_publish){
            $product_publish = date("Y-m-d H:i:s");
        }

        $product = ProductModel::findOrFail($id);

        $product->product_name = $product_name;
        $product->category_id = $category_id;
        $product->product_status = $product_status;
        $product->product_desc = $product_desc;
        $product->product_publish = $product_publish;
        $product->product_quantity = $product_quantity;
        $product->product_price = $product_price;

        if ($request->hasFile('product_image')){
            if ($product->product_image){
                Storage::delete($product->product_image);
            }
            $pathProductImage = $request->file('product_image')->store('public/productimages');
            $product->product_image = $pathProductImage;
        }
        $product->save();

        return redirect("/backend/product/edit2/$id")->with('status','Sửa sản phẩm thành công');

    }

    //Xoá sản phẩm
    public function destroy($id){

        echo "<br>id: ".$id;

        //Laấy đối tượng model dựa vào biến $id
        $product = ProductModel::findOrFail($id);
        $product->delete();

        return redirect("/backend/product/index")->with('status','Xoá sản phẩm thành công');
    }

}

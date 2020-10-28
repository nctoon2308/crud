<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Backend\CategoryModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    //

    public function index(){
        //$category = CategoryModel::all();
        $category = DB::table('category')->paginate(10);
        //truyen du lieu xuong view
        $data = [];
        $data["category"] = $category;
        return view("backend.category.index",$data);
    }

    public function create(){
        return view("backend.category.create");
    }

    public function store(Request $request){

        //validate
        $validatedData = $request->validate([
            'category_name' => 'required',
            'category_image' => 'required',
            'category_slug' => 'required',
            'category_desc' => 'required',


        ]);

        $category_name = $request->input('category_name','');
        $category_desc = $request->input('category_desc','');
        $category_slug = $request->input('category_slug','');
        $pathcategoryImage = $request->file('category_image')->store('public/categoryimages');



        $category = new CategoryModel();




        $category->category_name = $category_name;
        $category->category_desc = $category_desc;
        $category->category_slug = $category_slug;
        //image tạm thời sẽ rỗng
        $category->category_image = $pathcategoryImage;

        $category->save();

        //chuyển hướng
        return redirect("/backend/category/index")->with('status','Thêm danh mục thành công');
    }

    public function delete($id){
        $category = CategoryModel::findOrFail($id);

        $data = [];
        $data["category"] = $category;
        return view("backend.category.delete",$data);
    }

    //Xoá sản phẩm
    public function destroy($id){

        $countProducts = DB::table('products')->where('category_id',$id)->count();

        if ($countProducts > 0) {
            return redirect("/backend/category/index")->with('status', 'xóa tất cả các sản phẩm thuộc danh mục này trước khi xóa danh mục !');
        }
        //Laấy đối tượng model dựa vào biến $id
        $category = CategoryModel::findOrFail($id);
        $category->delete();

        return redirect("/backend/category/index")->with('status','Xoá danh mục thành công');
    }

    public function edit2($id){
        $category = CategoryModel::findOrFail($id);

        $data = [];
        $data["category"] = $category;



        return view("backend.category.edit2",$data);
    }

    public function update(Request $request, $id){

        $validatedData = $request->validate([
            'category_name' => 'required',
            'category_image' => 'required',
            'category_slug' => 'required',
            'category_desc' => 'required',
        ]);

        echo "<br>id: ".$id;
        echo "<pre>";
        print_r($_POST);
        echo "</pre>";

        $category_name = $request->input('category_name','');
        $category_desc = $request->input('category_desc','');
        $category_slug = $request->input('category_slug','');
        $pathcategoryImage = $request->file('category_image')->store('public/categoryimages');



        $category = CategoryModel::findOrFail($id);

        $category->category_name = $category_name;
        $category->category_desc = $category_desc;
        $category->category_slug = $category_slug;
        //image tạm thời sẽ rỗng
        $category->category_image = $pathcategoryImage;

        if ($request->hasFile('category_image')){
            if ($category->category_image){
                Storage::delete($category->category_image);
            }
            $pathcategoryImage = $request->file('category_image')->store('public/categoryimages');
            $category->category_image = $pathcategoryImage;
        }
        $category->save();

        return redirect("/backend/category/edit2/$id")->with('status','Sửa danh mục thành công');

    }
}

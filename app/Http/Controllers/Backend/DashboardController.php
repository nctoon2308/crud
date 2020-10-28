<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Backend\ProductModel;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //

    public function index(Request $request){
        $products = ProductModel::all();
        $data[] = [];
        $data["products"] = $products;
        $searchKeyword = $request->query('product_name','');
        $data["searchKeyword"] = $searchKeyword;
        return view("backend.dashboard.home",$data);
    }
}

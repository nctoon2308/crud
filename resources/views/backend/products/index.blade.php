@extends('backend.layouts.main')

@section('title','Danh sách sản phẩm')

@section('content')
    <h1>Danh sách sản phẩm</h1>
    <div style="padding: 10px; border: 1px solid #4e73df; margin-bottom: 10px">
        <form action="{{htmlspecialchars($_SERVER["REQUEST_URI"])}}" name="search_product" method="get" class="form-inline">

            <input name="product_name" value="" class="form-control" style="width: 350px; margin-right: 20px" placeholder="Nhập tên sản phẩm bạn cần tìm" autocomplete="off">
            <div style="padding: 10px 0">
                <input type="submit" name="search" class="btn btn-success" value="Lọc kết quả">
            </div>
            <input type="hidden" name="page" value="1">
        </form>
    </div>
    {{$products->links()}}

    @if(session('status'))
        <div class="alert alert-success">
            {{session('status')}}
        </div>
    @endif

    <div style="padding: 20px">
        <a href="{{url("/backend/product/create")}}" class="btn btn-info">Thêm sản phẩm</a>
    </div>

    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead>
        <tr>
            <th>Mã sản phẩm</th>
            <th>Ảnh đại diện</th>
            <th>Tên sản phẩm</th>
            <th>Giá sản phẩm</th>
            <th>Tồn kho</th>
            <th>Hành động</th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <th>Mã sản phẩm</th>
            <th>Ảnh đại diện</th>
            <th>Tên sản phẩm</th>
            <th>Giá sản phẩm</th>
            <th>Tồn kho</th>
            <th>Hành động</th>
        </tr>
        </tfoot>
        <tbody>
        @if(isset($products) && !empty($products))
            @foreach($products as $product)
        <tr>
            <td>{{$product->id}}</td>
            <td>
                @if($product->product_image)
                    <?php
                    $product->product_image = str_replace("public/","",$product->product_image);
                    ?>
                    <div>
                        <img src="{{asset("storage/$product->product_image")}}" style="width: 200px; height: auto" alt="">
                    </div>
                @endif
            </td>
            <td>{{$product->product_name}}</td>
            <td>{{$product->product_price}}</td>
            <td>{{$product->product_quantity}}</td>
            <td>
                <a href="{{url("/backend/product/edit2/$product->id")}}" class="btn btn-warning">Sửa sản phẩm</a>
                <a href="{{url("/backend/product/delete/$product->id")}}" class="btn btn-danger">Xóa sản phẩm</a>
            </td>
        </tr>
            @endforeach
        @else
         Chua co ban ghi nao
        @endif


        </tbody>
    </table>
    {{$products->links()}}
@endsection

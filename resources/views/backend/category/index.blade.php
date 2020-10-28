@extends('backend.layouts.main')

@section('title','Danh sách danh mục')

@section('content')
    <h1>Danh sách danh mục</h1>


    @if(session('status'))
        <div class="alert alert-success">
            {{session('status')}}
        </div>
    @endif

    <div style="padding: 20px">
        <a href="{{url("/backend/category/create")}}" class="btn btn-info">Thêm sản phẩm</a>
    </div>

    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead>
        <tr>
            <th>Mã danh mục</th>
            <th>Ảnh danh mục</th>
            <th>Tên danh mục</th>
            <th>Hành động</th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <th>Mã danh mục</th>
            <th>Ảnh danh mục</th>
            <th>Tên danh mục</th>
            <th>Hành động</th>
        </tr>
        </tfoot>
        <tbody>
        @if(isset($category) && !empty($category))
            @foreach($category as $cate)
                <tr>
                    <td>{{$cate->id}}</td>
                    <td>
                        @if($cate->category_image)
                            <?php
                            $cate->category_image = str_replace("public/","",$cate->category_image);
                            ?>
                            <div>
                                <img src="{{asset("storage/$cate->category_image")}}" style="width: 200px; height: auto" alt="">
                            </div>
                        @endif
                    </td>
                    <td>{{$cate->category_name}}</td>
                    <td>
                        <a href="{{url("/backend/category/edit2/$cate->id")}}" class="btn btn-warning">Sửa sản phẩm</a>
                        <a href="{{url("/backend/category/delete/$cate->id")}}" class="btn btn-danger">Xóa sản phẩm</a>
                    </td>
                </tr>
            @endforeach
        @else
            Chua co ban ghi nao
        @endif

        </tbody>
    </table>

@endsection

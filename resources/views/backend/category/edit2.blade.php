@extends('backend.layouts.main')

@section('title','Sửa danh mục')

@section('content')
    <h1>Sửa sản phẩm</h1>
    @if(session('status'))
        <div class="alert alert-success">
            {{session('status')}}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $error)
                <li>{{$error}}</li>
            @endforeach
        </div>
    @endif

    <form enctype="multipart/form-data" name="category" method="post" action="{{url("/backend/category/update/$category->id")}}">
        @csrf
        <div class="form-group">
            <label for="category_name">Tên sản phẩm:</label>
            <input value="{{$category->category_name}}" type="text" name="category_name" class="form-control" id="category_name">
        </div>

        <div class="form-group">
            <label for="category_image">Ảnh sản phẩm:</label>
            <input type="file" class="form-control" name="category_image" id="category_image">

            @if($category->category_image)
                <?php
                $category->category_image = str_replace("public/","",$category->category_image);
                ?>
                <div>
                    <img src="{{asset("storage/$category->category_image")}}" style="width: 200px; height: auto" alt="">
                </div>
            @endif

        </div>
        <div class="form-group">
            <label for="category_image">Mô tả sản phẩm:</label>
            <textarea name="category_desc" class="form-control" id="category_desc"  rows="10">{{$category->category_desc}}</textarea>
        </div>
        <div>
            <label for="category_image">Preview Mô tả sản phẩm:</label>
            <div>
                {!!$category->category_desc!!}
            </div>
        </div>

        <button type="submit" class="btn btn-info">Cập nhật sản phẩm</button>
    </form>
@endsection
@section('appendjs')

@endsection


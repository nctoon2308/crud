@extends('backend.layouts.main')

@section('title','Tạo mới danh mục')

@section('content')
    <h1>Tạo mới danh mục</h1>
    @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $error)
                <li>{{$error}}</li>
            @endforeach
        </div>
    @endif

    <form enctype="multipart/form-data" name="category" method="post" action="{{url("/backend/category/store")}}">
        @csrf
        <div class="form-group">
            <label for="category_name">Tên danh mục:</label>
            <input type="text" name="category_name" class="form-control" id="category_name" value="{{old('category_name')}}">
        </div>
        <div class="form-group">
            <label for="category_name">Slug danh mục:</label>
            <input type="text" name="category_slug" class="form-control" id="category_slug" value="{{old('category_slug')}}">
        </div>
        <div class="form-group">
            <label for="category_image">Ảnh danh mục:</label>
            <input type="file" class="form-control" name="category_image" id="category_image" value="{{old('category_image')}}">
        </div>
        <div class="form-group">
            <label for="category_image">Mô tả danh mục:</label>
            <textarea name="category_desc" class="form-control" id="category_desc"  rows="10">{{old('category_desc')}}</textarea>
        </div>
        <button type="submit" class="btn btn-info">Thêm danh mục</button>
    </form>
@endsection

@section('appendjs')

@endsection

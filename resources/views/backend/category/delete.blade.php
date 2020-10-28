@extends('backend.layouts.main')

@section('title','Xoá danh mục')

@section('content')
    <h1>Xoá danh mục</h1>
    @if(session('status'))
        <div class="alert alert-success">
            {{session('status')}}
        </div>
    @endif
    <form action="{{url("/backend/category/destroy/$category->id")}}" method="post" name="category">
        @csrf
        <div class="form-group">
            <label for="category_name">ID danh mục:</label>
            <p>{{$category->id}}</p>
        </div>
        <div class="form-group">
            <label for="category_name">Tên danh mục:</label>
            <p>{{$category->pcategory_name}}</p>
        </div>
        <button type="submit" class="btn btn-danger">Xác nhận xoá</button>
    </form>
@endsection

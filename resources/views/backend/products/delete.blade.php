@extends('backend.layouts.main')

@section('title','Xoá sản phẩm')

@section('content')
    <h1>Xoá sản phẩm</h1>
    @if(session('status'))
        <div class="alert alert-success">
            {{session('status')}}
        </div>
    @endif
    <form action="{{url("/backend/product/destroy/$product->id")}}" method="post" name="product">
        @csrf
        <div class="form-group">
            <label for="product_name">ID sản phẩm:</label>
            <p>{{$product->id}}</p>
        </div>
        <div class="form-group">
            <label for="product_name">Tên sản phẩm:</label>
            <p>{{$product->product_name}}</p>
        </div>
        <button type="submit" class="btn btn-danger">Xác nhận xoá</button>
    </form>
@endsection

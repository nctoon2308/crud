<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model;

class ProductModel extends Model
{
    //
    //Khai báo tên bảng
    protected $table = 'products';


    //Khai báo khoá chính
    protected $primaryKey = 'id';
}

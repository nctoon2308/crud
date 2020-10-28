<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model;

class CategoryModel extends Model
{
    //
    //Khai báo tên bảng
    protected $table = 'category';


    //Khai báo khoá chính
    protected $primaryKey = 'id';
}

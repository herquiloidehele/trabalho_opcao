<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoriaProduto extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'categoria_produtos';
    protected $fillable = ['designacao', ];


    public function produtos(){
        return $this->hasMany('App\Models\Produto', 'categoria_produtos_id');
    }



}

<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class TestCategory extends Model {

    protected $table        = 'test_categories';
    protected $connection   = 'hubapp';
    protected $fillable      = ['test_id','subcategory', 'category','tests','time','status','data'];

    protected $casts = [
        'data' => 'array',
    ];



}
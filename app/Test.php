<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Test extends Model {

    protected $table        = 'tests';
    protected $connection   = 'hubapp';

    public function test_categories(){
        return $this->hasMany('\App\TestCategory', 'test_id', 'id');
    }

}
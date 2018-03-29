<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Incident extends Model {

    protected $table = 'incidents';

    public function site(){
        return $this->hasOne('App\Site', 'id', 'site_id');
    }
}
<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Response extends Model {

    protected $table = 'responses';
    protected $fillable = ['site_id', 'time'];

    public function getDayAttribute()
    {
        return date("d-M i:s", strtotime($this->created_at));
    }
    public function getWeekAttribute()
    {
        return date("d-M", strtotime($this->created_at));
    }
    public function getMonthAttribute()
    {
        return date("d-M", strtotime($this->created_at));
    }

}
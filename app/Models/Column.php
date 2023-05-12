<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Column extends Model {

    use HasFactory;

    protected $table = 'columns';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'values'
    ];

    public static function by_table($table) {
        return Column::where('name', $table)->first();
    }

    public static function get_values($table) {
        $table = Column::where('name', $table)->first();
        return (!empty($table)) ? json_decode($table->values, true) : null;
    }

    public static function update_values($table, $values) {
        return Column::where('name', $table)->update(['values' => json_encode($values)]);
    }

}

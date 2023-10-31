<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MapModel extends Model
{
    use HasFactory;

    protected $table = 'map_datas';

    protected $fillable = [
		'latitude',
		'longitude',
	    'place_name',
        'url',
        'information',
        'sheet_A',
        'sheet_B',
        'marker',
    ];

}

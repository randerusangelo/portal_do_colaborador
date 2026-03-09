<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class LogAptidao extends Model
{
    protected $table = "LOG_APTIDAO";

    protected $fillable = [
        "DATA_HORA",
        "MATRICULA",
        "DIA_NASC",
        "IP_ORIGEM",
    ];

    public $incrementing = false;
    public $timestamps   = false;
}

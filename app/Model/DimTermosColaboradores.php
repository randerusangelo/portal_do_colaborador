<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class DimTermosColaboradores extends Model
{
    protected $table      = 'DIM_TERMOS_COLABORADORES';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'ID';

    const CREATED_AT      = 'CREATED_AT';
    const UPDATED_AT      = 'UPDATED_AT';

    public $incrementing  = false;

    static public function getMaxCreated()
    {
        return DimTermosColaboradores::max('CREATED_AT');
    }

    static public function show()
    {
        return DimTermosColaboradores::where('CREATED_AT', DimTermosColaboradores::getMaxCreated() )
                                       ->select([
                                           'TITULO',
                                           'TEXTO'
                                       ])
                                       ->get();
    }

    static public function showId()
    {
        $id = DimTermosColaboradores::where('CREATED_AT', DimTermosColaboradores::getMaxCreated() )
                                       ->first( 'ID' );
        return $id->ID;
    }
}
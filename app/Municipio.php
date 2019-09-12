<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * \App\Municipio
 *
 * @property int $co_municipio
 * @property string $no_municipio
 * @property int $co_uf
 * @property int|null $qt_populacao
 * @method static Builder|Municipio newModelQuery()
 * @method static Builder|Municipio newQuery()
 * @method static Builder|Municipio query()
 * @method static Builder|Municipio whereCoMunicipio($value)
 * @method static Builder|Municipio whereCoUf($value)
 * @method static Builder|Municipio whereNoMunicipio($value)
 * @method static Builder|Municipio whereQtPopulacao($value)
 * @mixin Eloquent
 */
class Municipio extends Model
{


    public function uf(){
        return $this->belongsTo(UF::class,'co_uf','co_uf');
    }

}

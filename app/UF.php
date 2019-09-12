<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * \App\UF
 *
 * @property int $co_uf
 * @property string $no_uf
 * @property string $no_estado
 * @property-read Collection|Municipio[] $municipios
 * @method static Builder|UF newModelQuery()
 * @method static Builder|UF newQuery()
 * @method static Builder|UF query()
 * @method static Builder|UF whereCoUf($value)
 * @method static Builder|UF whereNoEstado($value)
 * @method static Builder|UF whereNoUf($value)
 * @mixin Eloquent
 */
class UF extends Model
{
    protected $table = 'uf';

    public function municipios(){
        return $this->hasMany(Municipio::class,'co_uf','co_uf');
    }

}

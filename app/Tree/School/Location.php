<?php


namespace App\Tree\School;

use App\Municipio;
use App\Tree\Branch;
use App\Tree\DecisionTree;
use App\UF;
use Closure;
use DB;
use Google\Cloud\Language\V1\Entity;
use Google\Cloud\Language\V1\Entity\Type as EntityType;
use Illuminate\Database\Eloquent\Builder;

class Location extends Branch
{
    function handle(DecisionTree $tree, Closure $next): DecisionTree
    {
        dump("Checking for location constraint");

        $locations = collect($tree->getAnnotateTextResponse()->getEntities())->filter(function(Entity $entity){
            return $entity->getType() == EntityType::LOCATION;
        });

        if ($locations->isNotEmpty()) {

            $uf = null;
            $municipio = null;

            foreach ($locations as $location) {

                /** @noinspection SpellCheckingInspection */
                $normalized_location = to_lowercase($location->getName());

                /** @var UF $uf */
                $uf = $uf ?? UF::query()
                        ->where(DB::raw('lower(unaccent(no_estado))'), '=', $normalized_location)
                        ->orWhere(DB::raw('lower(no_uf)'),'=',$normalized_location)
                        ->first();
            }

            foreach ($locations as $location) {

                /** @noinspection SpellCheckingInspection */
                $normalized_location = to_lowercase($location->getName());

                /** @var Municipio $municipio */
                $municipio = $municipio ?? Municipio::query()
                        ->where(DB::raw('lower(unaccent(no_municipio))'),'=',$normalized_location)
                        ->where(function(Builder $query) use ($uf) {
                            if ($uf) $query->where('co_uf','=',$uf->co_uf);
                        })
                        ->orderByRaw('qt_populacao DESC NULLS LAST')
                        ->first();
            }

            if ($municipio) {
                $tree->getQuery()->where('co_municipio','=',$municipio->co_municipio);
            } else if ($uf) {
                $tree->getQuery()->where('co_uf','=',$uf->co_uf);
            }
        }

        return $next($tree);
    }

}

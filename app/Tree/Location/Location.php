<?php


namespace App\Tree\Location;


use App\Decorators\Token;
use App\Tree\Answer;
use App\Tree\Branch;
use App\Tree\DecisionTree;
use Closure;
use Google\Cloud\Language\V1\DependencyEdge\Label;

class Location extends Branch
{
    function handle(DecisionTree $tree, Closure $next): DecisionTree
    {
        /** @var Token $root */
        $root = $tree->getRootToken();

        /** @noinspection PhpUndefinedMethodInspection */
        $root_dependency_lemma = $root->getDependencies()->map->getLemma()->map(function($lemma){
            return to_lowercase($lemma);
        })->toArray();

        if (array_intersect($root_dependency_lemma,['cidade','estado','localidade'])){
            $tree->getAnswer()->setEntity(Answer::ENTITY_LOCATION);
            return $tree;
        } else {
            return $next($tree);
        }

    }

}

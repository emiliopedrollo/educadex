<?php


namespace App\Tree\School;

use App\Decorators\Token;
use App\Escola;
use App\Tree\Answer;
use App\Tree\Branch;
use App\Tree\DecisionTree;
use Closure;
use Google\Cloud\Language\V1\DependencyEdge\Label;
use Illuminate\Pipeline\Pipeline;

class School extends Branch
{
    function handle(DecisionTree $tree, Closure $next): DecisionTree
    {
        $tokens = $tree->getTokens();

        /** @var Token $root */
        $root = $tokens->first(function (Token $token){
            return $token->getLabel() == Label::ROOT;
        });

        /** @noinspection PhpUndefinedMethodInspection */
        $root_dependency_lemma = $root->getDependencies()->map->getLemma()->map(function($lemma){
            return to_lowercase($lemma);
        })->toArray();

        if (array_intersect($root_dependency_lemma,['escola','colegio','instituto'])){

            $tree->setQuery(Escola::query());

            dump("subject is a SCHOOL");
            $tree->getAnswer()->setEntity(Answer::ENTITY_SCHOOL);

            return app(Pipeline::class)
                ->send($tree)
                ->through([
                    Location::class,
                    Level::class,
                    Order::class
                ])->thenReturn();

        } else {
            return $next($tree);
        }

    }

}

<?php


namespace App\Tree\School;

use App\Decorators\Token;
use App\Tree\Branch;
use App\Tree\DecisionTree;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class Order extends Branch
{

    function multipleLevels(Token $token, array &$levels = [], string &$order = null) {

        $match = true;

        if (preg_match('/alunos/',to_lowercase($token->getLemma()))) {
            $levels[] = ['alunos'];
        } else if (in_array(to_lowercase($token->getLemma()),['numero','quantidade','de'])) {
            // Do nothing
        } else {
            $match = false;
        }


        if ($match) {
            if (is_null($order) and ($prep = $token->getDependencies()->first(function (Token $token) {
                    return in_array(to_lowercase($token->getLemma()),['crescente','ascendente','inversa','decrescente']);
                }))
            ) {
                /** @var Token $prep */
                $order = in_array($prep->getLemma(), ['crescente','ascendente']) ? 'asc' : 'desc';
            }

            foreach ($token->getDependencies() as $dependency) {
                $this->multipleLevels($dependency,$levels, $order);
            }
        }
    }


    function handle(DecisionTree $tree, Closure $next): DecisionTree
    {
        dump("Checking for level constraint");

        /** @var Token $root */
        $root = $tree->getTokens()->first(function(Token $token) {
            return in_array(to_lowercase($token->getLemma()),['ordem','ordenado']);
        });

        foreach ($root->getDependencies() as $token) {

            $levels = [];
            $boolean = null;
            foreach ($token->getDependencies() as $dependency) {
                $this->multipleLevels($dependency,$levels, $boolean);
            }

        }

        return $next($tree);
    }

}

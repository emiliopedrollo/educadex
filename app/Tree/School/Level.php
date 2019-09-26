<?php


namespace App\Tree\School;

use App\Decorators\Token;
use App\Tree\Branch;
use App\Tree\DecisionTree;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class Level extends Branch
{

    function multipleLevels(Token $token, array &$levels = [], string &$boolean = null) {

        $match = true;

        if (preg_match('/e\.?j\.?a\.?/',to_lowercase($token->getLemma()))) {
            $levels[] = ['in_eja'];
        } else if (preg_match('/proff?i(ss?|c)ional[ie][zs]s?ant[ei]/',to_lowercase($token->getLemma()))) {
            $levels[] = 'in_profissionalizante';
        } else if (preg_match('/(educa[cç][aã]o|en[sc]ino)/',to_lowercase($token->getLemma()))) {
            // Do nothing
        } else if (preg_match('/medio/',to_lowercase($token->getLemma()))) {
            $levels[] = ['in_comum_medio_medio','in_comum_medio_integrado','in_comum_medio_normal'];
        } else if (preg_match('/fundamental/',to_lowercase($token->getLemma()))) {
            $levels[] = ['in_comum_eja_fund','in_esp_exclusiva_eja_fund',
                'in_comum_fund_ai','in_comum_fund_af',
                'in_esp_exclusiva_fund_ai','in_esp_exclusiva_fund_af'];
        } else if (preg_match('/especial/',to_lowercase($token->getLemma()))) {
            $levels[] = 'in_especial_exclusiva';
        } else if (preg_match('/cre(ch|x)e/',to_lowercase($token->getLemma()))) {
            $levels[] = 'in_comum_creche';
        } else if (preg_match('/semi-?presencial/',to_lowercase($token->getLemma()))) {
            $levels[] = 'in_mediacao_semipresencial';
        } else if (preg_match('/presencial/',to_lowercase($token->getLemma()))) {
            $levels[] = 'in_mediacao_presencial';
        } else if (preg_match('/e\.?a\.?d\.?/',to_lowercase($token->getLemma()))) {
            $levels[] = 'in_mediacao_ead';
        } else if (preg_match('/pre/',to_lowercase($token->getLemma()))) {
            $levels[] = 'in_comum_pre';
        } else {
            $match = false;
        }


        if ($match) {
            if (is_null($boolean) and ($prep = $token->getDependencies()->first(function (Token $token) {
                    return in_array(to_lowercase($token->getLemma()),['e','ou']);
                }))
            ) {
                /** @var Token $prep */
                $boolean = $prep->getLemma() == 'e' ? 'and' : 'or';
            }

            foreach ($token->getDependencies() as $dependency) {
                $this->multipleLevels($dependency,$levels, $boolean);
            }
        }
    }


    function handle(DecisionTree $tree, Closure $next): DecisionTree
    {
        dump("Checking for level constraint");

        /** @var Token $root */
        $root = $tree->getRootToken();

        foreach ($root->getDependencies() as $token) {

            switch (to_lowercase($token->getLemma())) {
                case 'de':
                case 'com':
                    $expected = true;
                    break;
                case 'sem':
                    $expected = false;
                    break;
                case 'ter':
                    $expected = $token->getDependencies()->filter(function (Token $token){
                        return to_lowercase($token->getLemma()) == 'nao';
                    })->isEmpty();
                    break;
                default:
                    continue 2;
            }

            $levels = [];
            $boolean = null;
            foreach ($token->getDependencies() as $dependency) {
                $this->multipleLevels($dependency,$levels, $boolean);
            }

            $tree->getQuery()->where(function(Builder $query) use ($levels, $expected, $boolean) {
                foreach ($levels as $level) {
                    if (is_string($level)) {
                        $query->where($level,'=',$expected,$boolean);
                    } else if (is_array($level)) {
                        $query->where(function (Builder $query) use ($level,$expected) {
                            foreach ($level as $sub_level) {
                                $query->orWhere($sub_level,'=',$expected);
                            }
                        },null,null,$boolean);
                    }
                }
            });
        }

        return $next($tree);
    }

}

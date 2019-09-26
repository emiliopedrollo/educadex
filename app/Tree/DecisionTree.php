<?php


namespace App\Tree;


use App\Decorators\Token;
use App\Tree\Location\Location;
use App\Tree\School\School;
use Cache;
use DB;
use Google\Cloud\Language\V1\AnnotateTextRequest\Features;
use Google\Cloud\Language\V1\AnnotateTextResponse;
use Google\Cloud\Language\V1\DependencyEdge\Label;
use Google\Cloud\Language\V1\Document;
use Google\Cloud\Language\V1\Document\Type;
use Google\Cloud\Language\V1\LanguageServiceClient;
use Google\Cloud\Language\V1\PartOfSpeech\Number;
use Google\Cloud\Language\V1\Token as BaseToken;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class DecisionTree
{

    /**
     * @var AnnotateTextResponse
     */
    private $annotateTextResponse;

    /**
     * @var Collection
     */
    private $tokens;

    /**
     * @var Builder
     */
    private $query;
    /**
     * @var string
     */
    private $sentence;

    /**
     * @var Answer
     */
    private $answer;

    public function __construct(string $sentence)
    {
        $this->sentence = trim(preg_replace('/\s+/',' ',$sentence));
        $this->answer = new Answer;
        $this->query = null;
    }

    public function process(): Answer {
        /** @var DecisionTree $tree */
        app(Pipeline::class)
            ->send($this)
            ->through([
                Location::class,
                School::class,
            ])->thenReturn();

        $answer = $this->getAnswer();

        if ($answer->isWithinDomain()) {
            foreach ($this->getTokens() as $token){
                /** @var BaseToken $token */
                if (strtolower($token->getLemma()) == 'quanto') {
                    $answer->setType(Answer::NUMBER);
                    $answer->setValue($this->getQuery()->count());
                }

                if (strtolower($token->getLemma()) == 'qual') {
                    if ($token->getPartOfSpeech()->getNumber() == Number::PLURAL) {
                        $answer->setType(Answer::LIST);
                        $limit = 100;
                        $entities = $this->getQuery()
                            ->select(['escolas.*', DB::raw('count(*) OVER (PARTITION BY NULL) AS total')])
                            ->limit($limit)
                            ->get();

                        if ($entities->isEmpty()) {
                            $answer = "Sua pesquisa não retornou resultados.";
                        } else {
                            $total = $entities->first()->total;
                            if ($total > $limit) {
                                $answer->addWarning(sprintf(
                                    "Sua pesquisa teve muitos hits. Mostrando apenas os %d primeiros de %d",
                                    $limit, $total
                                ));
                            }
                            $answer->setValue($entities->map->no_entidade->toArray());
                        }
                    } else {
                        $answer->setType(Answer::NAME);
                        $answer->setValue($this->getQuery()->first()->no_entidade);
                    }
                }
            }
        } else {
            $answer->setType(Answer::UNKNOWN);
        }

        return $answer;
    }

    public function getTokens(): Collection
    {
        if ($this->tokens === null){
            $key = 0;
            $this->tokens = collect();
            collect($this->getAnnotateTextResponse()->getTokens())->each(function(BaseToken $token) use (&$key){
                $this->tokens->add(new Token($token, $this->tokens,$key++));
            });
        }
        return $this->tokens;
    }

    public function getRootToken() : Token
    {
        return $this->getTokens()->first(function (Token $token) {
            return $token->getLabel() == Label::ROOT;
        });
    }

    /**
     * @return AnnotateTextResponse
     */
    public function getAnnotateTextResponse(): AnnotateTextResponse
    {
        if ($this->annotateTextResponse === null) {
            /** @var Carbon $next_week */
            $next_week = now()->addWeek();

            $this->annotateTextResponse = unserialize(Cache::remember(
                "sentence|{$this->sentence}", $next_week, function() {
                    $languageServiceClient = new LanguageServiceClient(['projectId' => env('GOOGLE_PROJECT_ID')]);

                    $document = (new Document())
                        ->setContent($this->sentence)
                        ->setType(Type::PLAIN_TEXT);

                    $features = (new Features())
                        ->setExtractEntities(true)
                        ->setExtractSyntax(true);

                    return serialize($languageServiceClient->annotateText($document, $features));
                })
            );
        }

        return $this->annotateTextResponse;
    }

    /**
     * @return Builder
     */
    public function getQuery(): Builder
    {
        return $this->query;
    }

    /**
     * @param Builder $query
     * @return DecisionTree
     */
    public function setQuery(Builder $query): DecisionTree
    {
        $this->query = $query;
        return $this;
    }

    /**
     * @return string
     */
    public function getSentence(): string
    {
        return $this->sentence;
    }

    public function getAnswer()
    {
        return $this->answer;
    }

}

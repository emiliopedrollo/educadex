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
use Log;

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

    protected function normalizeSentence(string $sentence): string {
        $sentence = trim(preg_replace('/\s+/',' ',$sentence));
        $sentence = preg_replace('/(?<=\b\s)(\b[A-Z]{2}\b)/',' - \\1',$sentence);
        return $sentence;
    }

    public function __construct(string $sentence)
    {
        Log::notice("Question: $sentence");
        $this->sentence = $this->normalizeSentence($sentence);
        $this->answer = new Answer;
        $this->query = null;
    }

    protected function getNumberAnswer(Answer $answer) {
        $answer->setType(Answer::NUMBER);
        $answer->setValue($this->getQuery()->count());
    }

    protected function getListAnswer(Answer $answer, $limit = 100) {
        $answer->setType(Answer::LIST);
        $entities = $this->getQuery()
            ->select(['escolas.*', DB::raw('count(*) OVER (PARTITION BY NULL) AS total')])
            ->limit($limit)
            ->get();

        if ($entities->isEmpty()) {
            $answer->setValue("Sua pesquisa não retornou resultados.");
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
    }

    protected function getSingleEntityAnswer(Answer $answer) {
        $answer->setType(Answer::NAME);
        $answer->setValue($this->getQuery()->first()->no_entidade);
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
                /** @var Token $token */
                if (strtolower($token->getLemma()) == 'quanto') {
                    $this->getNumberAnswer($answer);
                }

                if (strtolower($token->getLemma()) == 'qual') {
                    if ($token->getPartOfSpeech()->getNumber() == Number::PLURAL) {
                        $this->getListAnswer($answer);
                    } else {
                        $this->getSingleEntityAnswer($answer);
                    }
                }

                if (strtolower($token->getLemma()) == 'listar') {

                    if (($number = $token->getDependenciesFlat(3)->filter(function (Token $token){
                        return is_numeric($token->getContent());
                    }))->isNotEmpty()
                    ) {
                        $this->getListAnswer($answer, (int) $number->first()->getContent());
                    } else {
                        $this->getListAnswer($answer);
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

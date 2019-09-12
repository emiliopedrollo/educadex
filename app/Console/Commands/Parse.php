<?php

namespace App\Console\Commands;

use App\Escola;
use App\Municipio;
use App\UF;
use DB;
use Google\ApiCore\ApiException;
use Google\ApiCore\ValidationException;
use Google\Cloud\Language\V1\AnnotateTextRequest\Features;
use Google\Cloud\Language\V1\AnnotateTextResponse;
use Google\Cloud\Language\V1\Document;
use Google\Cloud\Language\V1\Document\Type;
use Google\Cloud\Language\V1\Entity;
use Google\Cloud\Language\V1\Entity\Type as EntityType;
use Google\Cloud\Language\V1\EntityMention;
use Google\Cloud\Language\V1\EntityMention\Type as MentionType;
use Google\Cloud\Language\V1\LanguageServiceClient;
use Google\Cloud\Language\V1\PartOfSpeech\Aspect;
use Google\Cloud\Language\V1\PartOfSpeech\Form;
use Google\Cloud\Language\V1\PartOfSpeech\Gender;
use Google\Cloud\Language\V1\PartOfSpeech\Mood;
use Google\Cloud\Language\V1\PartOfSpeech\Number;
use Google\Cloud\Language\V1\PartOfSpeech\PBCase;
use Google\Cloud\Language\V1\PartOfSpeech\Person;
use Google\Cloud\Language\V1\PartOfSpeech\Proper;
use Google\Cloud\Language\V1\PartOfSpeech\Reciprocity;
use Google\Cloud\Language\V1\PartOfSpeech\Tag;
use Google\Cloud\Language\V1\PartOfSpeech\Tense;
use Google\Cloud\Language\V1\PartOfSpeech\Voice;
use Google\Cloud\Language\V1\Token;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class Parse extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse {sentence} {--A|with-analysis}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws ApiException
     * @throws ValidationException
     */
    public function handle()
    {

        $languageServiceClient = new LanguageServiceClient(['projectId' => env('GOOGLE_PROJECT_ID')]);

        $document = (new Document())
            ->setContent($this->argument('sentence'))
            ->setType(Type::PLAIN_TEXT);
        // Set Features to extract ['entities', 'syntax', 'sentiment']
        $features = (new Features())
            ->setExtractEntities(true)
            ->setExtractSyntax(true);

        // Collect annotations
        $response = $languageServiceClient->annotateText($document, $features);

        if ($this->option('with-analysis')) {
            $analysis = $this->analyze_all($response);
            $this->output->write($analysis);
        }
        // Process Entities
        $entities = $response->getEntities();

        $locations = [];
        $subjects = [];
        $filters = [];

        foreach ($entities as $entity) {
            /** @var Entity $entity */
            switch ($entity->getType()) {
                case EntityType::LOCATION:
                    $locations[] = $entity;
                    break;
                case EntityType::ORGANIZATION:
                    $subjects[] = $entity;
                    break;
                case EntityType::OTHER:
                    $filters[] = $entity;
                    break;
            }
        }

        if (empty($subjects)) {
            $this->error("Desculpe, não entendi sua pergunta");
            return 1;
        } else {

            $subject = Arr::first($subjects);

            $lemma = collect($response->getTokens())
                ->filter(function(Token $token) use ($subject) {
                    return $token->getText()->getContent() == $subject->getName();
                })->first()
                ->getLemma();

            if (in_array(strtolower($lemma),['escola','colégio','instituto'])) {
                $query = Escola::query();
            } else {
                $this->error("Você deve perguntar alguma coisa sobre escolas");
                return 1;
            }
        }

        if (!empty($locations)) {

            $uf = null;
            $municipio = null;

            foreach ($locations as $location) {

                /** @noinspection SpellCheckingInspection */
                $normalized_location = mb_strtolower(iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $location->getName()));

                /** @var UF $uf */
                $uf = $uf ?? UF::query()
                    ->where(DB::raw('lower(unaccent(no_estado))'), '=', $normalized_location)
                    ->orWhere(DB::raw('lower(no_uf)'),'=',$normalized_location)
                    ->first();
            }

            foreach ($locations as $location) {

                /** @noinspection SpellCheckingInspection */
                $normalized_location = mb_strtolower(iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $location->getName()));

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
                $query->where('co_municipio','=',$municipio->co_municipio);
            } else if ($uf) {
                $query->where('co_uf','=',$uf->co_uf);
            }
        }

        $tokens = $response->getTokens();
        $response_type = 'UNKNOWN';
        $answer = 'Não conseguimos encontrar um resposta para você.';
        foreach ($tokens as $token){
            /** @var Token $token */
            if (strtolower($token->getLemma()) == 'quanto') {
                $response_type = 'Numero';
                $answer = $query->count();
            }

            if (strtolower($token->getLemma()) == 'qual') {
                if ($token->getPartOfSpeech()->getNumber() == Number::PLURAL) {
                    $response_type = 'Lista';
                    $answer = join(PHP_EOL,$query->get()->map->no_entidade->toArray());
                } else {
                    $response_type = 'Node de entidade';
                    $answer = $query->first()->no_entidade;
                }
            }
        }

        $this->output->note(sprintf("Tipo de resposta: %s", $response_type));

        $this->output->success($answer);

        return 0;
    }


    /**
     * @param AnnotateTextResponse $response
     * @return string
     */
    function analyze_all(AnnotateTextResponse $response)
    {
        // Process Entities
        $entities = $response->getEntities();
        $output = '';
        foreach ($entities as $entity) {
            $output .= sprintf('Name: %s' . PHP_EOL, $entity->getName());
            $output .= sprintf('Type: %s' . PHP_EOL, EntityType::name($entity->getType()));
            $output .= sprintf('Salience: %s' . PHP_EOL, $entity->getSalience());
            if ($entity->getMetadata()->offsetExists('wikipedia_url')) {
                $output .= sprintf('Wikipedia URL: %s' . PHP_EOL, $entity->getMetadata()->offsetGet('wikipedia_url'));
            }
            if ($entity->getMetadata()->offsetExists('mid')) {
                $output .= sprintf('Knowledge Graph MID: %s' . PHP_EOL, $entity->getMetadata()->offsetGet('mid'));
            }
            $output .= sprintf('Mentions:' . PHP_EOL);
            foreach ($entity->getMentions() as $mention) {
                /** @var EntityMention $mention */
                $output .= sprintf('  Begin Offset: %s' . PHP_EOL, $mention->getText()->getBeginOffset());
                $output .= sprintf('  Content: %s' . PHP_EOL, $mention->getText()->getContent());
                $output .= sprintf('  Mention Type: %s' . PHP_EOL, MentionType::name($mention->getType()));
                $output .= sprintf(PHP_EOL);
            }
            $output .= sprintf(PHP_EOL);
        }
        // Process Syntax
        $tokens = $response->getTokens();
        // Print out information about each entity
        $i = 0;
        foreach ($tokens as $token) {
            /** @var Token $token */
            $output .= sprintf('Token index: %d' . PHP_EOL, $i++);
            $output .= sprintf('Token text: %s' . PHP_EOL, $token->getText()->getContent());
            $output .= sprintf('Token part of speech: %s' . PHP_EOL, Tag::name($token->getPartOfSpeech()->getTag()));
            $output .= sprintf('Token dependency edge: %s' . PHP_EOL, $token->getDependencyEdge()->serializeToJsonString());
            $output .= sprintf('Token lemma: %s' . PHP_EOL, $token->getLemma());
            $output .= sprintf('Token aspect: %s' . PHP_EOL, Aspect::name($token->getPartOfSpeech()->getAspect()));
            $output .= sprintf('Token case: %s' . PHP_EOL, PBCase::name($token->getPartOfSpeech()->getCase()));
            $output .= sprintf('Token form: %s' . PHP_EOL, Form::name($token->getPartOfSpeech()->getForm()));
            $output .= sprintf('Token gender: %s' . PHP_EOL, Gender::name($token->getPartOfSpeech()->getGender()));
            $output .= sprintf('Token mood: %s' . PHP_EOL, Mood::name($token->getPartOfSpeech()->getMood()));
            $output .= sprintf('Token number: %s' . PHP_EOL, Number::name($token->getPartOfSpeech()->getNumber()));
            $output .= sprintf('Token person: %s' . PHP_EOL, Person::name($token->getPartOfSpeech()->getPerson()));
            $output .= sprintf('Token proper: %s' . PHP_EOL, Proper::name($token->getPartOfSpeech()->getProper()));
            $output .= sprintf('Token reciprocity: %s' . PHP_EOL, Reciprocity::name($token->getPartOfSpeech()->getReciprocity()));
            $output .= sprintf('Token tense: %s' . PHP_EOL, Tense::name($token->getPartOfSpeech()->getTense()));
            $output .= sprintf('Token voice: %s' . PHP_EOL, Voice::name($token->getPartOfSpeech()->getVoice()));
            $output .= sprintf(PHP_EOL);
        }
        return $output;
    }
}

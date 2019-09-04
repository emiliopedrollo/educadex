<?php

namespace App\Http\Controllers;

use Google\ApiCore\ApiException;
use Google\ApiCore\ValidationException;
use Google\Cloud\Language\V1\AnnotateTextResponse;
use Google\Cloud\Language\V1\Document;
use Google\Cloud\Language\V1\Entity;
use Google\Cloud\Language\V1\EntityMention;
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
use Google\Cloud\Language\V1\PartOfSpeech\Tense;
use Google\Cloud\Language\V1\PartOfSpeech\Voice;
use Google\Cloud\Language\V1\Sentence;
use Google\Cloud\Language\V1\Token;
use Google\Cloud\Language\V1\AnnotateTextRequest\Features;
use Google\Cloud\Language\V1\Document\Type;
use Google\Cloud\Language\V1\Entity\Type as EntityType;
use Google\Cloud\Language\V1\EntityMention\Type as MentionType;
use Google\Cloud\Language\V1\PartOfSpeech\Tag;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{

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

    /**
     * @param $text
     * @param null $projectId
     * @throws ApiException
     * @throws ValidationException
     */
    function analyze_entities($text, $projectId = null)
    {
        // Create the Natural Language client
        $languageServiceClient = new LanguageServiceClient(['projectId' => $projectId]);
        try {
            $entity_types = [
                0 => 'UNKNOWN',
                1 => 'PERSON',
                2 => 'LOCATION',
                3 => 'ORGANIZATION',
                4 => 'EVENT',
                5 => 'WORK_OF_ART',
                6 => 'CONSUMER_GOOD',
                7 => 'OTHER',
                8 => 'PHONE_NUMBER',
                9 => 'ADDRESS',
                10 => 'DATE',
                11 => 'NUMBER',
                12 => 'PRICE'
            ];
            $document = new Document();
            // Add text as content and set document type to PLAIN_TEXT
            $document->setContent($text)->setType(1);
            // Call the analyzeEntities function
            $response = $languageServiceClient->analyzeEntities($document, []);
            $entities = $response->getEntities();
            // Print out information about each entity
            foreach ($entities as $entity) {
                printf('Name: %s' . '<br/>', $entity->getName());
                printf('Type: %s' . '<br/>', $entity_types[$entity->getType()]);
                printf('Salience: %s' . '<br/>', $entity->getSalience());
                if ($entity->getMetadata()->offsetExists('wikipedia_url')) {
                    printf('Wikipedia URL: %s' . '<br/>', $entity->getMetadata()->offsetGet('wikipedia_url'));
                }
                if ($entity->getMetadata()->offsetExists('mid')) {
                    printf('Knowledge Graph MID: %s' . '<br/>', $entity->getMetadata()->offsetGet('mid'));
                }
                printf('<br/>');
            }
        } finally {
            $languageServiceClient->close();
        }
    }

    /**
     * @param $text
     * @param null $projectId
     * @throws ApiException
     * @throws ValidationException
     */
    function analyze_syntax($text, $projectId = null)
    {
        // Create the Natural Language client
        $languageServiceClient = new LanguageServiceClient(['projectId' => $projectId]);

        try {
            $tag_types = [
                0 => 'UNKNOWN',
                1 => 'ADJ',
                2 => 'ADP',
                3 => 'ADV',
                4 => 'CONJ',
                5 => 'DET',
                6 => 'NOUN',
                7 => 'NUM',
                8 => 'PRON',
                9 => 'PRT',
                10 => 'PUNCT',
                11 => 'VERB',
                12 => 'X',
                13 => 'AFFIX',
            ];
            // Create a new Document
            $document = new Document();
            // Add text as content and set document type to PLAIN_TEXT
            $document->setContent($text)->setType(Document\Type::PLAIN_TEXT);
            // Call the analyzeEntities function
            $response = $languageServiceClient->analyzeSyntax($document, []);

            $sentences = $response->getSentences();
            foreach ($sentences as $sentence) {
                /** @var Sentence $sentence */
                printf('Sentence: %s <br/>',$sentence->serializeToJsonString());
                print('<br/>');
            }

            $tokens = $response->getTokens();
            // Print out information about each entity
            foreach ($tokens as $token) {
                /** @var Token $token */
                printf('Token text: %s' . '<br/>', $token->getText()->getContent());
                printf('Token part of speech: %s' . '<br/>', $tag_types[$token->getPartOfSpeech()->getTag()]);
                printf('Token dependency edge: %s' . '<br/>', $token->getDependencyEdge()->serializeToJsonString());
                print('<br/>');
            }
        } finally {
            $languageServiceClient->close();
        }
    }

    /**
     * @param Request $request
     * @return Factory|View
     * @throws ApiException
     * @throws ValidationException
     */
    public function search(Request $request) {

        $languageServiceClient = new LanguageServiceClient(['projectId' => env('GOOGLE_PROJECT_ID')]);

        $document = (new Document())
            ->setContent($request->input('search'))
            ->setType(Type::PLAIN_TEXT);
        // Set Features to extract ['entities', 'syntax', 'sentiment']
        $features = (new Features())
            ->setExtractEntities(true)
            ->setExtractSyntax(true);

        // Collect annotations
        $response = $languageServiceClient->annotateText($document, $features);

        $analysis = $this->analyze_all($response);
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

        $tokens = $response->getTokens();
        $response_type = 'Informação';
        foreach ($tokens as $token){
            /** @var Token $token */
            if (strtolower($token->getLemma()) == 'quanto') {
                $response_type = 'Numero';
            }

            if (strtolower($token->getLemma()) == 'qual') {
                if ($token->getPartOfSpeech()->getNumber() == Number::PLURAL) {
                    $response_type = 'Lista';
                } else {
                    $response_type = 'Node de entidade';
                }
            }
        }


        $languageServiceClient->close();
        return view('answer',compact('analysis','locations', 'subjects','filters','response_type'));
    }
}

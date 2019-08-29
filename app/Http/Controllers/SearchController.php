<?php

namespace App\Http\Controllers;

use Google\ApiCore\ApiException;
use Google\ApiCore\ValidationException;
use Google\Cloud\Language\V1\Document;
use Google\Cloud\Language\V1\EntityMention;
use Google\Cloud\Language\V1\LanguageServiceClient;
use Google\Cloud\Language\V1\Sentence;
use Google\Cloud\Language\V1\Token;
use Google\Cloud\Language\V1\AnnotateTextRequest\Features;
use Google\Cloud\Language\V1\Document\Type;
use Google\Cloud\Language\V1\Entity\Type as EntityType;
use Google\Cloud\Language\V1\EntityMention\Type as MentionType;
use Google\Cloud\Language\V1\PartOfSpeech\Tag;
use Illuminate\Http\Request;

class SearchController extends Controller
{

    /**
     * @param $text
     * @param $projectId
     * @throws ApiException
     * @throws ValidationException
     */
    function analyze_all($text,$projectId) {

        // Create the Natural Language client
        $languageServiceClient = new LanguageServiceClient(['projectId' => $projectId]);
        try {
            // Create a new Document, pass text and set type to PLAIN_TEXT
            $document = (new Document())
                ->setContent($text)
                ->setType(Type::PLAIN_TEXT);
            // Set Features to extract ['entities', 'syntax', 'sentiment']
            $features = (new Features())
                ->setExtractEntities(true)
                ->setExtractSyntax(true)
                ->setExtractDocumentSentiment(true);
            // Collect annotations
            $response = $languageServiceClient->annotateText($document, $features);
            // Process Entities
            $entities = $response->getEntities();
            foreach ($entities as $entity) {
                printf('Name: %s' . '<br/>', $entity->getName());
                printf('Type: %s' . '<br/>', EntityType::name($entity->getType()));
                printf('Salience: %s' . '<br/>', $entity->getSalience());
                if ($entity->getMetadata()->offsetExists('wikipedia_url')) {
                    printf('Wikipedia URL: %s' . '<br/>', $entity->getMetadata()->offsetGet('wikipedia_url'));
                }
                if ($entity->getMetadata()->offsetExists('mid')) {
                    printf('Knowledge Graph MID: %s' . '<br/>', $entity->getMetadata()->offsetGet('mid'));
                }
                printf('Mentions:' . '<br/>');
                foreach ($entity->getMentions() as $mention) {
                    /** @var EntityMention $mention */
                    printf('  Begin Offset: %s' . '<br/>', $mention->getText()->getBeginOffset());
                    printf('  Content: %s' . '<br/>', $mention->getText()->getContent());
                    printf('  Mention Type: %s' . '<br/>', MentionType::name($mention->getType()));
                    printf('<br/>');
                }
                printf('<br/>');
            }
            // Process Sentiment
            $document_sentiment = $response->getDocumentSentiment();
            // Print document information
            printf('Document Sentiment:' . '<br/>');
            printf('  Magnitude: %s' . '<br/>', $document_sentiment->getMagnitude());
            printf('  Score: %s' . '<br/>', $document_sentiment->getScore());
            printf('<br/>');
            $sentences = $response->getSentences();
            foreach ($sentences as $sentence) {
                /** @var Sentence $sentence */
                printf('Sentence: %s' . '<br/>', $sentence->getText()->getContent());
                printf('Sentence Sentiment:' . '<br/>');
                $sentiment = $sentence->getSentiment();
                if ($sentiment) {
                    printf('Entity Magnitude: %s' . '<br/>', $sentiment->getMagnitude());
                    printf('Entity Score: %s' . '<br/>', $sentiment->getScore());
                }
                printf('<br/>');
            }
            // Process Syntax
            $tokens = $response->getTokens();
            // Print out information about each entity
            foreach ($tokens as $token) {
                /** @var Token $token */
                printf('Token text: %s' . '<br/>', $token->getText()->getContent());
                printf('Token part of speech: %s' . '<br/>', Tag::name($token->getPartOfSpeech()->getTag()));
                printf('Token dependency edge: %s' . '<br/>', $token->getDependencyEdge()->serializeToJsonString());
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
     * @throws ApiException
     * @throws ValidationException
     */
    public function search(Request $request) {
        $this->analyze_all($request->input('search'),env('GOOGLE_PROJECT_ID'));
//        printf('<h3>Entidade</h3>');
//        $this->analyze_entities($request->input('search'),env('GOOGLE_PROJECT_ID'));
//        printf('<h3>Sintaxe</h3>');
//        $this->analyze_syntax($request->input('search'),env('GOOGLE_PROJECT_ID'));
    }
}

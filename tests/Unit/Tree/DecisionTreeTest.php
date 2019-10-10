<?php

namespace Tests\Unit\Tree;

use App\Tree\Answer;
use App\Tree\DecisionTree;
use Tests\TestCase;

class DecisionTreeTest extends TestCase
{

    public function testSpecialSchoolCountInSantaMariaRSFormat1()
    {
        $tree = new DecisionTree('Quantas escolas de ensino especial existem em Santa Maria, RS?');

        $answer = $tree->process();

        $this->assertEquals(Answer::NUMBER, $answer->getType());
        $this->assertIsInt($answer->getValue());
        $this->assertEquals(6, $answer->getValue());
    }

    public function testListHasALimitByDefault()
    {
        $tree = new DecisionTree(
            'Quais as escolas de São Paulo?'
        );

        $answer = $tree->process();

        $this->assertNotEmpty($answer->getWarnings());
        $this->assertCount(100,$answer->getValue());
    }

    public function testListWithExplicitNumberOfResults()
    {
        $tree = new DecisionTree(
            'Listar 400 escolas de São Paulo?'
        );

        $answer = $tree->process();

        $this->assertCount(400,$answer->getValue());
    }

    public function testListWithExplicitNumberAboveCap()
    {
        $tree = new DecisionTree(
            'Listar 501 escolas de São Paulo?'
        );

        $answer = $tree->process();

        $this->assertNotEmpty($answer->getWarnings());
        $this->assertCount(500,$answer->getValue());
    }

    public function testListNumberOfSchoolsOfInclusiveLevelsAtSomeCity()
    {
        $tree = new DecisionTree(
            'Listar 10 escolas de ensino especial, ensino fundamental ou profissionalizante de Santa Maria, RS?'
        );

        $answer = $tree->process();

        $expectations = [
            'ESC EST ENS FUN DR ANTONIO XAVIER DA ROCHA',
            'COL ESTADUAL CORONEL PILAR',
            'INST EST EDUC OLAVO BILAC',
            'APAE SANTA MARIA',
            'ESCOLA ANTONIO FRANCISCO LISBOA',
            'E E DE EDUC ESP DR REINALDO FERNANDO COSER',
        ];

        $this->assertEquals(Answer::LIST, $answer->getType());

        foreach ($expectations as $expected) {
            $this->assertTrue(in_array($expected, $answer->getValue()));
        }

        $this->assertEquals(count($expectations), count($answer->getValue()));
    }


    public function testListNumberOfSchoolsOfExclusiveLevelsAtSomeCity(){
        $tree = new DecisionTree(
            'Listar escolas de ensino especial, fundamental e profissionalizante de Santa Maria, RS?'
        );

        $answer = $tree->process();

        $expectations = ['INST EST EDUC OLAVO BILAC',
            'E E DE EDUC ESP DR REINALDO FERNANDO COSER'];

        $this->assertEquals(Answer::LIST,$answer->getType());

        foreach ($expectations as $expected){
            $this->assertTrue(in_array($expected,$answer->getValue()));
        }

        $this->assertEquals(count($expectations),count($answer->getValue()));
    }

}

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

        $this->assertIsInt($answer->getValue());
        $this->assertEquals(6,$answer->getValue());

        $this->assertEquals(Answer::NUMBER,$answer->getType());
    }
}

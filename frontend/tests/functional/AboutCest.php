<?php

namespace frontend\tests\functional;

use frontend\tests\FunctionalTester;

class AboutCest
{
    public function checkAbout(FunctionalTester $I): void
    {
        $I->amOnRoute('site/about');
        $I->see('About', 'h1');
    }
}

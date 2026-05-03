<?php

namespace frontend\tests;

use Codeception\Actor;
use frontend\tests\_generated\FunctionalTesterActions;
use Codeception\Lib\Friend;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void verify($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(PHPMD)
 */
class FunctionalTester extends Actor
{
    use FunctionalTesterActions;


    public function seeValidationError($message): void
    {
        $this->see($message, '.invalid-feedback');
    }

    public function dontSeeValidationError($message): void
    {
        $this->dontSee($message, '.invalid-feedback');
    }
}

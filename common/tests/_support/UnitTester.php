<?php

namespace common\tests;

use Codeception\Actor;
use common\tests\_generated\UnitTesterActions;
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
class UnitTester extends Actor
{
    use UnitTesterActions;
   /**
    * Define custom actions here
    */
}

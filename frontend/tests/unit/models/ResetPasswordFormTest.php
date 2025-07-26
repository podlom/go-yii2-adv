<?php

namespace frontend\tests\unit\models;

use Codeception\Test\Unit;
use frontend\tests\UnitTester;
use common\fixtures\UserFixture;
use frontend\models\ResetPasswordForm;

class ResetPasswordFormTest extends Unit
{
    /**
     * @var UnitTester
     */
    protected $tester;


    public function _before(): void
    {
        $this->tester->haveFixtures([
            'user' => [
                'class' => UserFixture::class,
                'dataFile' => codecept_data_dir() . 'user.php'
            ],
        ]);
    }

    public function testResetWrongToken(): void
    {
        $this->tester->expectThrowable('\yii\base\InvalidArgumentException', function(): void {
            new ResetPasswordForm('');
        });

        $this->tester->expectThrowable('\yii\base\InvalidArgumentException', function(): void {
            new ResetPasswordForm('notexistingtoken_1391882543');
        });
    }

    public function testResetCorrectToken(): void
    {
        $user = $this->tester->grabFixture('user', 0);
        $form = new ResetPasswordForm($user['password_reset_token']);
        verify($form->resetPassword())->notEmpty();
    }

}

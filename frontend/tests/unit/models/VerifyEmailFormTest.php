<?php

namespace frontend\tests\unit\models;

use Codeception\Test\Unit;
use common\models\User;
use frontend\tests\UnitTester;
use common\fixtures\UserFixture;
use frontend\models\VerifyEmailForm;

class VerifyEmailFormTest extends Unit
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
            ]
        ]);
    }

    public function testVerifyWrongToken(): void
    {
        $this->tester->expectThrowable('\yii\base\InvalidArgumentException', function(): void {
            new VerifyEmailForm('');
        });

        $this->tester->expectThrowable('\yii\base\InvalidArgumentException', function(): void {
            new VerifyEmailForm('notexistingtoken_1391882543');
        });
    }

    public function testAlreadyActivatedToken(): void
    {
        $this->tester->expectThrowable('\yii\base\InvalidArgumentException', function(): void {
            new VerifyEmailForm('already_used_token_1548675330');
        });
    }

    public function testVerifyCorrectToken(): void
    {
        $model = new VerifyEmailForm('4ch0qbfhvWwkcuWqjN8SWRq72SOw1KYT_1548675330');
        $user = $model->verifyEmail();
        verify($user)->instanceOf('common\models\User');

        verify($user->username)->equals('test.test');
        verify($user->email)->equals('test@mail.com');
        verify($user->status)->equals(User::STATUS_ACTIVE);
        verify($user->validatePassword('Test1234'))->true();
    }
}

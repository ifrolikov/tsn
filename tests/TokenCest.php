<?php

namespace admin;


use ApiTester;
use app\components\managers\PasswordManager;
use app\models\Token;
use app\models\User;
use Faker\Factory;
use Faker\Generator;

class TokenCest
{
    /** @var Generator */
    private $faker;

    public function _before(ApiTester $I)
    {
        $this->faker = Factory::create();
    }

    public function _after(ApiTester $I)
    {
    }

    /**
     * @param ApiTester $I
     * @throws \Exception
     */
    public function testCreate(ApiTester $I)
    {
        $data = $this->getCreateData();
        $I->sendPOST('token/create', $data);
        $I->seeResponseCodeIs(200);
        $I->seeResponseMatchesJsonType(['token' => 'string']);
        /** @var Token $token */
        $token = $I->grabRecord(Token::class, ['token' => $I->grabDataFromResponseByJsonPath('$.token')]);
        $token->user->delete();
    }

    /**
     * @return array
     * @throws \Exception
     */
    private function getCreateData()
    {
        $passwordManager = new PasswordManager();
        $password = $this->faker->password;
        $user = createModel(User::class, [
            'email' => $this->faker->email,
            'phone' => $this->faker->phoneNumber,
            'first_name' => $this->faker->firstName,
            'password' => $passwordManager->setPassword($password)->encrypt()
        ]);

        return [
            'phone' => $user->phone,
            'password' => $password
        ];
    }
}

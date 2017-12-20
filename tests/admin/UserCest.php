<?php

namespace admin;


use ApiTester;
use app\components\managers\PasswordManager;
use app\components\rbac\Roles;
use app\models\Token;
use app\models\User;
use Faker\Factory;
use Faker\Generator;
use yii\di\Instance;

/**
 * Class UserCest
 * @package admin
 */
class UserCest
{
    /** @var Generator */
    private $faker;
    /** @var Token */
    private $token;
    /** @var Token */
    private $successToken;

    /**
     * @param ApiTester $I
     * @throws \Exception
     */
    public function _before(ApiTester $I)
    {
        $this->faker = $this->faker ?? Factory::create();
        $this->token = createToken();
        $this->successToken = createToken([Roles::USER_MANAGEMENT['role']]);
    }

    /**
     * @param ApiTester $I
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function _after(ApiTester $I)
    {
        $this->token->user->delete();
    }

    /**
     * @param ApiTester $I
     */
    public function testCreate(ApiTester $I)
    {
        $request = 'admin/user';

        $data = $this->getCreateData();
        /** @var PasswordManager $passwordManager */
        $passwordManager = Instance::of(PasswordManager::class)->get();
        $encryptedPassword = $passwordManager->setPassword($data['password'])->encrypt();
        $verifyData = array_merge($data, [
            'password' => $encryptedPassword
        ]);

        $I->sendPOST($request, $data);
        $I->seeResponseCodeIs(401);

        $I->setHeader('accessToken', $this->token->token);
        $I->sendPOST($request, $data);
        $I->seeResponseCodeIs(403);

        $I->setHeader('accessToken', $this->successToken->token);
        $I->sendPOST($request, $data);
        $I->seeResponseCodeIs(200);

        unset($data['password']);
        $I->seeResponseMatchesJsonType($this->getUserResponse());
        $I->seeResponseContainsJson($data);
        $I->grabRecord(User::class, $verifyData);
    }

    /**
     * @param ApiTester $I
     */
    public function testUpdate(ApiTester $I)
    {
        $user = $this->token->user;
        $request = 'admin/user/' . $user->id;

        $data = $this->getUpdateData();
        $verifyData = array_merge($user->attributes, $data);

        $I->sendPATCH($request, $data);
        $I->seeResponseCodeIs(401);

        $I->setHeader('accessToken', $this->token->token);
        $I->sendPATCH($request, $data);
        $I->seeResponseCodeIs(403);

        $I->setHeader('accessToken', $this->successToken->token);
        $I->sendPATCH($request, $data);
        $I->seeResponseCodeIs(200);

        unset($data['password']);
        $I->seeResponseMatchesJsonType($this->getUserResponse());
        $I->seeResponseContainsJson($data);
        $I->grabRecord(User::class, $verifyData);
    }

    /**
     * @param ApiTester $I
     */
    public function testDelete(ApiTester $I)
    {
        $user = $this->token->user;
        $request = 'admin/user';
        $data = ['ids' => [$user->id]];

        $I->sendDELETE($request, $data);
        $I->seeResponseCodeIs(401);

        $I->setHeader('accessToken', $this->token->token);
        $I->sendDELETE($request, $data);
        $I->seeResponseCodeIs(403);

        $I->setHeader('accessToken', $this->successToken->token);
        $I->sendDELETE($request, $data);
        $I->seeResponseCodeIs(200);

        $record = $I->grabRecord(User::class, ['id' => $user->id]);
        $I->assertNotEmpty($record['deleted_at']);
    }

    public function testIndex(ApiTester $I)
    {
        $user = $this->token->user;
        $request = 'admin/user';
        $data = ['id' => [$user->id]];

        $I->sendGET($request, $data);
        $I->seeResponseCodeIs(401);

        $I->setHeader('accessToken', $this->token->token);
        $I->sendGET($request, $data);
        $I->seeResponseCodeIs(403);

        $I->setHeader('accessToken', $this->successToken->token);
        $I->sendGET($request, $data);
        $I->seeResponseCodeIs(200);
        $I->seeResponseMatchesJsonType([
            'items' => 'array',
            'total_count' => 'integer',
            'page_count' => 'integer',
            'page' => 'integer',
            'page_size' => 'integer'
        ]);
        $I->seeResponseMatchesJsonType($this->getUserResponse(), '$.items.*');
    }

    /**
     * @return array
     */
    private function getCreateData()
    {
        return [
            'email' => $this->faker->email,
            'phone' => $this->faker->phoneNumber,
            'password' => $this->faker->password,
            'first_name' => $this->faker->firstName,
            'second_name' => $this->faker->firstName,
            'father_name' => $this->faker->firstName
        ];
    }

    /**
     * @return array
     */
    private function getUpdateData()
    {
        $data = [
            'email' => $this->faker->email,
            'phone' => $this->faker->phoneNumber,
            'password' => $this->faker->password,
            'first_name' => $this->faker->firstName,
            'second_name' => $this->faker->firstName,
            'father_name' => $this->faker->firstName
        ];

        $randomRemoveField = [];
        $randomRemoveField[] = $this->faker->randomElement(array_keys($data));
        $randomRemoveField[] = $this->faker->randomElement(array_keys($data));
        $randomRemoveField[] = $this->faker->randomElement(array_keys($data));

        foreach ($randomRemoveField as $element) {
            unset($data[$element]);
        }

        return $data;
    }

    /**
     * @return array
     */
    private function getUserResponse()
    {
        return [
            'email' => 'string',
            'phone' => 'string',
            'first_name' => 'string',
            'second_name' => 'string',
            'father_name' => 'string'
        ];
    }
}

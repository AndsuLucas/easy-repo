<?php

declare(strict_types=1);

namespace Andsudev\Tests\Repository;

use PHPUnit\Framework\TestCase;
use Andsudev\Easyrepo\Repository\EloquentRepository;
use Andsudev\Easyrepo\DataEntry\AbstractDataEntry;

class EloquentRepositoryTest extends TestCase
{
    protected EloquentRepository $userRepo;


    public function setUp(): void
    {
        require_once __DIR__ . '/../../scripts/connect_elouquent_mysql_database.php';
        require_once __DIR__ . '/../../scripts/User.php';

        $this->userRepo = new class extends EloquentRepository
        {
            protected string $modelClass = \User::class;
        };

        parent::setUp();
    }

    public function testFindShouldReturnValidDataEntry() {
        $user = $this->userRepo->find(1);
        $this->assertIsObject($user);
        $this->assertInstanceOf(AbstractDataEntry::class, $user);
        $this->assertCount(1, $user);
        $this->assertEquals(1, $user->id);
        $this->assertEquals('John Doe', $user->name);
        $this->assertEquals('john.doe@example.com', $user->email);
    }

    public function testFindShouldReturnNullIfResgisterNotExists()
    {
        $user = $this->userRepo->find(10000000000000000);
        $this->assertNull($user);
    }

    public function testFindByShouldReturnValidDataEntryWithOrClause()
    {
        $users = $this->userRepo->findBy(['id', '=', '1'], ['or', 'id', '=', '2']);
        $this->assertIsObject($users);
        $this->assertInstanceOf(AbstractDataEntry::class, $users);
        $this->assertCount(2, $users);
    }

    public function testFindShouldReturnValidDataEntryWithAndClause()
    {
        $users = $this->userRepo->findBy(['age', '>=', '16'], ['and', 'id', '<=', '5']);
        $this->assertIsObject($users);
        $this->assertInstanceOf(AbstractDataEntry::class, $users);
        $this->assertCount(5, $users);
    }

    public function testFindByShouldReturnValidIterableDataEntry()
    {
        $users = $this->userRepo->findBy(['id', '=', '1'], ['or', 'id', '=', '2']);
        $this->assertIsIterable($users);
        $this->assertInstanceOf(AbstractDataEntry::class, $users);

        $expected = [
            ['id' => 1, 'name' => 'John Doe', 'email' => 'john.doe@example.com', 'age' => 64],
            ['id' => 2, 'name' => 'Jane Smith', 'email' => 'jane.smith@example.com', 'age' => 27]
        ];

        foreach ($users as $key => $item) {
            $this->assertEquals($item['id'], $expected[$key]['id']);
            $this->assertEquals($item['name'], $expected[$key]['name']);
            $this->assertEquals($item['email'], $expected[$key]['email']);
            $this->assertEquals($item['age'], $expected[$key]['age']);
            $this->assertEquals($item->id, $expected[$key]['id']);
            $this->assertEquals($item->name, $expected[$key]['name']);
            $this->assertEquals($item->email, $expected[$key]['email']);
            $this->assertEquals($item->age, $expected[$key]['age']);
        }
    }
}

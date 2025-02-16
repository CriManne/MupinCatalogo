<?php
declare(strict_types=1);

namespace App\Test\Repository\Computer;

use App\Models\GenericObject;
use App\Repository\GenericObjectRepository;
use App\Test\Repository\BaseRepositoryTest;
use App\Test\Repository\RepositoryTestUtil;
use PDO;
use PHPUnit\Framework\TestCase;
use AbstractRepo\Exceptions\RepositoryException as AbstractRepositoryException;
use App\Models\Computer\Computer;
use App\Models\Computer\Cpu;
use App\Models\Computer\Os;
use App\Models\Computer\Ram;
use App\Repository\Computer\ComputerRepository;
use App\Repository\Computer\CpuRepository;
use App\Repository\Computer\OsRepository;
use App\Repository\Computer\RamRepository;

final class ComputerRepositoryTest extends BaseRepositoryTest
{
    public static Computer      $sampleComputer;
    public static GenericObject $sampleGenericObject;
    public static Os            $sampleOs;
    public static Ram           $sampleRam;
    public static Cpu           $sampleCpu;

    public static GenericObjectRepository $genericObjectRepository;
    public static RamRepository           $ramRepository;
    public static CpuRepository           $cpuRepository;
    public static OsRepository            $osRepository;
    public static ComputerRepository      $computerRepository;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        // Repository to handle relations
        self::$genericObjectRepository = new GenericObjectRepository(self::$pdo);
        self::$ramRepository           = new RamRepository(self::$pdo);
        self::$cpuRepository           = new CpuRepository(self::$pdo);
        self::$osRepository            = new OsRepository(self::$pdo);


        // Repository to handle computer
        self::$computerRepository = new ComputerRepository(self::$pdo);

        self::$sampleGenericObject = new GenericObject(
            'objID',
            null,
            null,
            null
        );

        self::$sampleOs = new Os(
            "Windows",
            1
        );

        self::$sampleCpu = new Cpu(
            'Cpu 1.0',
            "2GHZ",
            1
        );

        self::$sampleRam = new Ram(
            "RAM 1.0",
            "4GB",
            1
        );

        self::$sampleComputer = new Computer(
            self::$sampleGenericObject,
            "Computer 1.0",
            2005,
            "1TB",
            self::$sampleCpu,
            self::$sampleRam,
            self::$sampleOs
        );

        self::$osRepository->save(self::$sampleOs);
        self::$cpuRepository->save(self::$sampleCpu);
        self::$ramRepository->save(self::$sampleRam);
    }

    public function setUp(): void
    {
        //Computer saved to test duplicated supports errors
        self::$genericObjectRepository->save(self::$sampleGenericObject);
        self::$computerRepository->save(self::$sampleComputer);
    }

    public function tearDown(): void
    {
        //Clear the table
        self::$pdo->exec("SET FOREIGN_KEY_CHECKS=0; TRUNCATE TABLE Computer; TRUNCATE TABLE GenericObject; SET FOREIGN_KEY_CHECKS=1;");
    }

    //INSERT TESTS
    public function testGoodInsert(): void
    {
        $genericObject     = clone self::$sampleGenericObject;
        $genericObject->id = "objID2";

        $computer                = clone self::$sampleComputer;
        $computer->genericObject = $genericObject;
        $computer->modelName     = "Computer 2";

        self::$genericObjectRepository->save($genericObject);
        self::$computerRepository->save($computer);

        $this->assertEquals(self::$computerRepository->findById("objID2")->modelName, "Computer 2");
    }

    public function testBadInsert(): void
    {
        $this->expectException(AbstractRepositoryException::class);
        //Computer already saved in the setUp() method
        self::$computerRepository->save(self::$sampleComputer);
    }

    //SELECT TESTS
    public function testGoodSelectById(): void
    {
        $this->assertNotNull(self::$computerRepository->findById("objID"));
    }

    public function testBadSelectById(): void
    {
        $this->assertNull(self::$computerRepository->findById("WRONGID"));
    }

    public function testGoodSelectByKey(): void
    {
        $genericObject     = clone self::$sampleGenericObject;
        $genericObject->id = "OBJ2";

        $computer                = clone self::$sampleComputer;
        $computer->genericObject = $genericObject;
        $computer->modelName     = "Computer 2";

        self::$genericObjectRepository->save($genericObject);
        self::$computerRepository->save($computer);

        $this->assertEquals(count(self::$computerRepository->findByQuery("comp")), 2);
    }

    public function testBadSelectByKey(): void
    {
        $this->assertEquals(self::$computerRepository->findByQuery("wrongkey"), []);
    }

    public function testGoodSelectAll(): void
    {
        $genericObject1     = clone self::$sampleGenericObject;
        $genericObject1->id = "objID1";
        $genericObject2     = clone self::$sampleGenericObject;
        $genericObject2->id = "objID2";
        $genericObject3     = clone self::$sampleGenericObject;
        $genericObject3->id = "objID3";

        $computer1                = clone self::$sampleComputer;
        $computer1->genericObject = $genericObject1;

        $computer2                = clone self::$sampleComputer;
        $computer2->genericObject = $genericObject2;

        $computer3                = clone self::$sampleComputer;
        $computer3->genericObject = $genericObject3;

        self::$genericObjectRepository->save($genericObject1);
        self::$genericObjectRepository->save($genericObject2);
        self::$genericObjectRepository->save($genericObject3);
        self::$computerRepository->save($computer1);
        self::$computerRepository->save($computer2);
        self::$computerRepository->save($computer3);

        $computers = self::$computerRepository->find();

        $this->assertEquals(count($computers), 4);
        $this->assertNotNull($computers[1]);
    }

    //UPDATE TESTS
    public function testGoodUpdate(): void
    {
        $computer            = clone self::$sampleComputer;
        $computer->modelName = "NEW MODELNAME";

        self::$computerRepository->update($computer);

        $this->assertEquals("NEW MODELNAME", self::$computerRepository->findById("objID")->modelName);
    }

    //DELETE TESTS
    public function testGoodDelete(): void
    {
        self::$computerRepository->delete("objID");

        $this->assertNull(self::$computerRepository->findById("objID"));
    }
}
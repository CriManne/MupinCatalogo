<?php
declare(strict_types=1);

namespace App\Test\Repository;

use PDO;
use PHPUnit\Framework\TestCase;
use App\Repository\Computer\CpuRepository;
use App\Exception\RepositoryException;
use App\Model\Computer\Cpu;

final class CpuRepositoryTest extends TestCase
{
    public static CpuRepository $cpuRepository;
    public static ?PDO $pdo;

    public static function setUpBeforeClass(): void
    {
        self::$pdo = RepositoryTestUtil::getTestPdo();

        self::$pdo = RepositoryTestUtil::dropTestDB(self::$pdo);
        self::$pdo = RepositoryTestUtil::createTestDB(self::$pdo);

        self::$cpuRepository = new CpuRepository(self::$pdo);          
    }

    public function setUp():void{
        //Cpu inserted to test duplicated cpu errors
        $cpu= new Cpu(null,'Cpu 1.0',"4GHZ",null);
        self::$cpuRepository->insert($cpu);        
    }

    public function tearDown():void{
        //Clear the table
        self::$pdo->exec("SET FOREIGN_KEY_CHECKS=0; TRUNCATE TABLE cpu; SET FOREIGN_KEY_CHECKS=1;");
    }

    //INSERT TESTS
    public function testGoodInsert():void{                
        $cpu= new Cpu(null,'Cpu 2.0',"4GHZ",null);

        self::$cpuRepository->insert($cpu);

        $this->assertEquals(self::$cpuRepository->selectById(2)->ModelName,"Cpu 2.0");
    }

    //No bad insert test because the ModelName is not unique.
    
    //SELECT TESTS
    public function testGoodSelectById(): void
    {
        $this->assertNotNull(self::$cpuRepository->selectById(1));
    }
    
    public function testBadSelectById(): void
    {
        $this->assertNull(self::$cpuRepository->selectById(3));
    }
    
    public function testGoodSelectByName(): void
    {
        $this->assertNotNull(self::$cpuRepository->selectByName("Cpu 1.0"));
    }
    
    public function testBadSelectByName(): void
    {
        $this->assertNull(self::$cpuRepository->selectByName("WRONG-CPU-NAME"));
    }
    
    
    public function testGoodSelectAll():void{
        $cpu1 = new Cpu(null,'Cpu 4.0',"4GHZ",null);
        $cpu2 = new Cpu(null,'Cpu 5.0',"8GHZ",null);
        $cpu3 = new Cpu(null,'Cpu 6.0',"12GHZ",null);
        self::$cpuRepository->insert($cpu1);
        self::$cpuRepository->insert($cpu2);
        self::$cpuRepository->insert($cpu3);
        
        $cpus = self::$cpuRepository->selectAll();
        
        $this->assertEquals(count($cpus),4);
        $this->assertNotNull($cpus[1]);       
    }    
    
    //UPDATE TESTS
    public function testGoodUpdate():void{
        $cpu= new Cpu(1,'Cpu 2.0',"4GHZ",null);
        
        self::$cpuRepository->update($cpu);
        
        $this->assertEquals("Cpu 2.0",self::$cpuRepository->selectById(1)->ModelName);
    }
    
    //DELETE TESTS
    public function testGoodDelete():void{       
        
        self::$cpuRepository->delete(1);
        
        $this->assertNull(self::$cpuRepository->selectById(1));
        $this->assertNotNull(self::$cpuRepository->selectById(1,true));
    }
    
    public static function tearDownAfterClass():void{
        self::$pdo = RepositoryTestUtil::dropTestDB(self::$pdo);        
        self::$pdo = null;
    }    
}
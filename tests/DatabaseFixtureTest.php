<?php

abstract class DatabaseFixtureTest extends PHPUnit_Extensions_Database_TestCase
{
    /**
     *
     * @var PDO
     */
    static private $pdo = null;

    /**
     *
     * @var type
     */
    private $conn = null;

    /**
     * Sets up the fixture
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $conn = $this->getConnection();
        $pdo = $conn->getConnection();
        // set up tables
        $fixtureDataSet = $this->getDataSet();
        foreach ($fixtureDataSet->getTableNames() as $table) {
            // drop table
            $pdo->exec("DROP TABLE IF EXISTS `$table`;");
            // recreate table
            $meta = $fixtureDataSet->getTableMetaData($table);
            $create = "CREATE TABLE IF NOT EXISTS `$table` ";
            $cols = array();
            foreach ($meta->getColumns() as $col) {
                $cols[] = "`$col` VARCHAR(200)";
            }
            $create .= '(' . implode(',', $cols) . ');';
            $pdo->exec($create);
        }
        parent::setUp();
    }

    /**
     * Tears down the fixture
     * This method is called after a test is executed.
     */
    public function tearDown()
    {
        $tables =
            $this->getDataSet()->getTableNames();
        foreach ($tables as $table) {
            // drop table
            $conn = $this->getConnection();
            $pdo = $conn->getConnection();
            $pdo->exec("DROP TABLE IF EXISTS `$table`;");
        }
        parent::tearDown();
    }


    protected function getConnection()
    {
				static $retries = 0;
			
        if ($this->conn === null) {
            if (self::$pdo == null) {
								do {
										try {
												self::$pdo = new PDO( $GLOBALS['DB_DSN'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD']);
										}
									  catch(PDOException $e) {
												$retries++;
												sleep(1);
												if($retries > 15) { throw $e; }
										}
								} while(self::$pdo == null);
            }
						$this->conn = $this->createDefaultDBConnection(self::$pdo, $GLOBALS['DB_DBNAME']);													
        }
        return $this->conn;
    }

    protected function getDataSet()
    {
        return $this->createXMLDataSet(__DIR__ . '/data/data.xml');
    }

    protected function loadDataSet() {
        // set the new data set
        $this->getDatabaseTester()->setDataSet($this->getDataSet());
        // adds rows whenever setup is called
        $this->getDatabaseTester()->onSetUp();
    }

}
<?php

include_once 'DatabaseFixtureTestCase.php';

class DatabaseTest extends DatabaseFixtureTest
{
    function testRead()
    {
        $conn = $this->getConnection()->getConnection();

        // fixtures auto loaded on setup
        // Read data
        $query = $conn->query('SELECT * FROM test_table');
        $results = $query->fetchAll(PDO::FETCH_COLUMN);
        $this->assertEquals(2, count($results));
    }

    function testTruncate()
    {
        $conn = $this->getConnection()->getConnection();
        // now delete them
        $conn->query('TRUNCATE test_table');
        $query = $conn->query('SELECT * FROM test_table');
        $results = $query->fetchAll(PDO::FETCH_COLUMN);
        $this->assertEquals(0, count($results));
    }

    function testReloadDatabase()
    {

        $conn = $this->getConnection()->getConnection();
        $conn->query('TRUNCATE test_table');
        //reloads data sets
        $this->loadDataSet();
        $query = $conn->query('SELECT * FROM test_table');
        $results = $query->fetchAll(PDO::FETCH_COLUMN);
        $this->assertEquals(2, count($results));
    }

    function testTableState()
    {
        $queryTable = $this->getConnection()->createQueryTable(
            'test_table', 'SELECT * FROM test_table'
        );
        $expectedTable = $this->getDataSet()->getTable("test_table");
        $this->assertTablesEqual($expectedTable, $queryTable);
    }

    function testInsert()
    {
        $sql = "INSERT INTO test_table (id, content) VALUES ('3', 'Added content')";
        $conn = $this->getConnection()->getConnection();
        $conn->exec($sql);

        $query = $conn->query('SELECT * FROM test_table');
        $results = $query->fetchAll(PDO::FETCH_COLUMN);
        $this->assertEquals(3, count($results));

        $queryTable = $this->getConnection()->createQueryTable(
            'test_table', 'SELECT * FROM test_table'
        );
        $expectedTable = $this->createXMLDataSet(__DIR__ . '/data/data_added.xml')->getTable("test_table");
        $this->assertTablesEqual($expectedTable, $queryTable);

    }

    function testUpdate()
    {
        $sql = "UPDATE test_table SET content='Docker Test Updated' WHERE id='2'";
        $conn = $this->getConnection()->getConnection();
        $conn->exec($sql);

        $queryTable = $this->getConnection()->createQueryTable(
            'test_table', 'SELECT * FROM test_table'
        );
        $expectedTable = $this->createXMLDataSet(__DIR__ . '/data/data_updated.xml')->getTable("test_table");
        $this->assertTablesEqual($expectedTable, $queryTable);

    }
}
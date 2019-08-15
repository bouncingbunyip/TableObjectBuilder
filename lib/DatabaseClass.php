<?php

/**
 * DatabaseClass
 *
 * @version $Id: $
 * @package TableObjectBuilder
 * @copyright 2019 Chris Hubbard
 */


/**
 * Description of DatabaseClass
 *
 * @author Chris Hubbard <chris@ourgourmetlife.com>
 */
class DatabaseClass
{

    protected $dsn = TOB_DSN;
    protected $user = TOB_DB_USER;
    protected $password = TOB_DB_PASSWORD;
    protected $dbh = ''; // database handle

    public function __construct()
    {
        $this->dbh = new PDO($this->dsn, $this->user, $this->password);
        $this->useDb();
    }

    public function useDb()
    {
        $sql = 'USE ' . TOB_DB_NAME;
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute();
    }

    public function listTables()
    {
        $sql = 'SHOW tables';
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $rows = $stmt->fetchAll();
        return $rows;
    }

    public function describeTable($table)
    {
        $sql = 'describe ' . $table;
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $rows = $stmt->fetchAll();
        return $rows;
    }
}

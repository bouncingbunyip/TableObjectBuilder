<?php

/**
 * ConfigClass
 *
 * @version $Id: $
 * @package TableObjectBuilder
 * @copyright 2019 Chris Hubbard
 */


/**
 * Description of ConfigClass
 *
 * @author Chris Hubbard <chris@ourgourmetlife.com>
 */
class ConfigClass
{

    protected $db_name = '';
    protected $db_user = '';
    protected $db_pass = '';

    public function __construct()
    {

    }

    public function init($data = false)
    {
        if ($data) {
            foreach ($data as $key => $value) {
                $this->$key = $value;
            }
        }
        $this->setDefines();
    }

    public function setDefines()
    {
        $defines = $this->getDefines();
        foreach ($defines as $key => $value) {
            if (!defined($key)) {
                define($key, $value);
            }
        }
    }

    public function getDefines()
    {
        $defines = array(
            'TOB_DB_NAME' => $this->db_name,
            'TOB_DB_HOST' => 'localhost',
            /** MySQL database username */
            'TOB_DB_USER' => $this->db_user,
            /** MySQL database password */
            'TOB_DB_PASSWORD' => $this->db_pass,
            /** Enable debugging for developers */
            'TOB_DEBUG' => true,
            'TOB_DSN' => 'mysql:dbname=' . $this->db_name . ';host=localhost',
            'TOB_TIMEZONE' => 'America/Los_Angeles'
        );
        return $defines;
    }
}

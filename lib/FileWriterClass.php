<?php

/**
 * FileWriterClass
 *
 * @version $Id: $
 * @package TableObjectBuilder
 * @copyright 2019 Chris Hubbard
 */


/**
 * Description of FileWriterClass
 *
 * @author Chris Hubbard <chris@ourgourmetlife.com>
 */
class FileWriterClass
{

    protected $path = 'generated/';
    protected $suffix = 'TableObject.php';

    public function __construct($path = null)
    {
        if (!is_null($path)) {
            $this->path = $path;
        }
    }

    public function write($name, $data)
    {
        $filename = $this->path . $name . $this->suffix;
        $rs = file_put_contents($filename, $data);
        $mode = substr(sprintf('%o', fileperms($filename)), -4);
        if ($mode !== '0777') {
            chmod($filename, 0777);
        }
        return $rs;
    }
}

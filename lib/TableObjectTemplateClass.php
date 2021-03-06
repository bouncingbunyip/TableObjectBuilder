<?php

/**
 * TableObjectTemplateClass
 *
 * @version $Id: $
 * @package TableObjectBuilder
 * @copyright 2019 Chris Hubbard
 */


/**
 * Description of TableObjectTemplateClass
 *
 * @author Chris Hubbard <chris@ourgourmetlife.com>
 */
class TableObjectTemplateClass
{

    public function __construct()
    {

    }

    public function buildClass($name, $fields)
    {
        $template = $this->getTemplate();
        $name = $this->getName($name);
        $protected = $this->buildProtected($fields);
        $setGet = $this->buildSetGet($fields);
        $arrayValues = $this->buildArrayValues($fields);
        date_default_timezone_set('UTC');
        $date = date('Y-m-d');
        $search = array('##name##', '##protected##', '##setGet##', '##array##', '##currentdate##');
        $replace = array($name, $protected, $setGet, $arrayValues, $date);
        $rendered = str_replace($search, $replace, $template);
        return $rendered;
    }

    public function getName($name)
    {
        $retval = $this->camelize(str_replace('vi_', '', $name));
        return $retval;
    }

    protected function getTemplate()
    {
        $retval = '<?php' . PHP_EOL
            . '/**' . PHP_EOL
            . ' * ##name##TableObject ' . PHP_EOL
            . ' * This class generated by TableObjectBuilder ' . PHP_EOL
            . ' * Do not manually edit this file.  Use the TableObjectBuilder to rebuild it instead. ' . PHP_EOL
            . ' * Any time there is a change to the database tables, the corresponding TableObject will need to be regenerated. ' . PHP_EOL
            . ' *  ' . PHP_EOL
            . ' * @version $Id $ ' . PHP_EOL
            . ' * @package  ' . PHP_EOL
            . ' * @copyright ' . PHP_EOL
            . ' * @internal created: ##currentdate##' . PHP_EOL
            . ' */  ' . PHP_EOL
            . '' . PHP_EOL
            . '/**' . PHP_EOL
            . ' * Description of ##name##TableObject ' . PHP_EOL
            . ' * ' . PHP_EOL
            . ' * This class acts as an interface to a single database table.' . PHP_EOL
            . ' * There should be no logic in the class, just a structure for storing a row. ' . PHP_EOL
            . ' * This is a mechanism for moving data around with a known correct structure' . PHP_EOL
            . ' * instead of using arrays. ' . PHP_EOL
            . ' *  ' . PHP_EOL
            . ' * Usage ' . PHP_EOL
            . ' * $data = array("activity_id"=>1, "activity_name"=>"Make Waves"); ' . PHP_EOL
            . ' * $activityObject = new ActivityTableObject($data); ' . PHP_EOL
            . ' *  ' . PHP_EOL
            . ' * @author ' . PHP_EOL
            . ' */  ' . PHP_EOL
            . '' . PHP_EOL
            . 'class ##name##TableObject {' . PHP_EOL
            . '' . PHP_EOL
            . '    /**' . PHP_EOL
            . '     * The list of protected properties should match the columns in the table' . PHP_EOL
            . '     */ ' . PHP_EOL
            . '##protected##' . PHP_EOL
            . '' . PHP_EOL
            . '    /**' . PHP_EOL
            . '     * __construct ' . PHP_EOL
            . '     * Expects an array with keys that match the class properties' . PHP_EOL
            . '     * @param array $data This array should have keys that correspond to the table\'s rows and values you want to put' . PHP_EOL
            . '     * into those rows.' . PHP_EOL
            . '     * @return NULL' . PHP_EOL
            . '     */' . PHP_EOL
            . '    public function __construct($data = false) {
        if ($data) {
            foreach ($data as $key=>$value) {
                if (property_exists($this, $key)) {
                    $this->set($key, $value);
                }
            }
        }
    }
    
    /**
     * asArray
     * Use this method to convert the TableObject into a keyless array with the class variables as the array values
     */
     public function asArray($data) {
         $retval = array(
##array##
         );
         return $retval;
     }
     
    /**
     * set
     * Use this method to set any of the keys.  Only works for keys that match the column names.
     *
     * @param string $key The name of the table column
     * @param mixed  $value Whatever you want to set the value of the column to
     * @return mixed Null on success, False if the key doesn\'t match a table column name
     */
    public function set($key, $value) {
        if (property_exists($this, $key)) {
            $this->$key = $value;
        } else {
            return false;
        }
    }
    
    public function get($key) {
        if (property_exists($this, $key)) {
            return $this->$key;
        } else {
            return false;
        }
    }' . PHP_EOL
            . '' . PHP_EOL
            . '    /**' . PHP_EOL
            . '     * Setters and Getters for each protected property' . PHP_EOL
            . '     */' . PHP_EOL
            . PHP_EOL
            . '##setGet##'
            . '}' . PHP_EOL
            . '';
        return $retval;
    }

    protected function buildProtected($fields)
    {
        $retval = '';
        foreach ($fields as $field) {
            $retval .= '    protected $' . $field['Field'] . ';' . PHP_EOL;
        }
        return $retval;
    }

    protected function buildSetGet($fields)
    {
        $retval = '';
        foreach ($fields as $field) {
            $camelized = $this->camelize($field['Field']);
            $retval .= '    /**' . PHP_EOL;
            $retval .= '     * set' . $camelized . PHP_EOL;
            $retval .= '     * Use this method to set the value for ' . $field['Field'] . PHP_EOL;
            $retval .= '     */' . PHP_EOL;
            $retval .= '    public function set' . $camelized . '($value) {' . PHP_EOL;
            $retval .= '        $this->' . $field['Field'] . ' = $value;' . PHP_EOL;
            $retval .= '    }' . PHP_EOL;
            $retval .= '' . PHP_EOL;
            $retval .= '    /**' . PHP_EOL;
            $retval .= '     * get' . $camelized . PHP_EOL;
            $retval .= '     * Use this method to get the value for ' . $field['Field'] . PHP_EOL;
            $retval .= '     */' . PHP_EOL;
            $retval .= '    public function get' . $camelized . '() {' . PHP_EOL;
            $retval .= '        return $this->' . $field['Field'] . ';' . PHP_EOL;
            $retval .= '    }' . PHP_EOL;
            $retval .= '' . PHP_EOL;
        }
        return $retval;
    }

    /**
     * Eventually this method should be removed, as it won't be needed.  The functionality of converting the TO into
     * an array should be kept temporarily in the Model class
     * @param $fields
     * @return string
     */
    protected function buildArrayValues($fields)
    {
        $retval = '';
        foreach ($fields as $field) {
            $retval .= '            $data->get' . $this->camelize($field['Field']) . '(),' . PHP_EOL;
        }
        return $retval;
    }

    public function camelize($string)
    {
        $retval = ucfirst(implode('', array_map('ucfirst', array_map('strtolower', explode('_', $string)))));
        return $retval;
    }
}


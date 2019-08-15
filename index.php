<?php

/**
 * Use this file to generate tableObjects.
 *
 * In it's current form, it gets a list of tables from the database, then
 * loops through those tables and gets a list of the fields in the table.
 * Then that data is given to the TableObjectTemplateClass which generates the
 * code that is written to file with the FileWriter class.
 *
 * When done use something like 'mv * ../path/to/project/tableObjects'
 * to move the generated files to the project folder.
 */
include 'lib/ConfigClass.php';
include 'lib/DatabaseClass.php';
include 'lib/TableObjectTemplateClass.php';
include 'lib/FileWriterClass.php';
if (!empty($_POST['submit'])) {
    $config = new ConfigClass();
    $config->init(array('db_name' => $_POST['dbname'], 'db_user' => $_POST['dbuser'], 'db_pass' => $_POST['dbpass']));

    $temp = new TableObjectTemplateClass();
    $file = new FileWriterClass();

    $db = new DatabaseClass();
    $tables = $db->listTables();
    $str = 'Tables_in_' . $_POST['dbname'];
    foreach ($tables as $table) {
        $tables_list[] = $table[$str];
    }

    foreach ($tables_list as $table) {
        $table_data = $db->describeTable($table);
        $rs = $temp->buildClass($table, $table_data);
        $result[$table] = $file->write($temp->getName($table), $rs);
    }

    $contents = '<h1>Results</h1>' . PHP_EOL
        . 'wrote tableObjects for: <br>' . PHP_EOL;
    foreach ($result as $key => $value) {
        $contents .= $key . '<br>' . PHP_EOL;
    }

} else {
    $contents = dbform();
}

function dbform()
{
    $html = '<form method="POST" action="index.php">' . PHP_EOL
        . '   <label for="dbname">Database Name</label>' . PHP_EOL
        . '       <input type="text" name="dbname" value="ogl"><br>' . PHP_EOL
        . '   <label for="dbuser">User</label>' . PHP_EOL
        . '       <input type="text" name="dbuser" value="root"><br>' . PHP_EOL
        . '   <label for="dbpass">Password</label>' . PHP_EOL
        . '       <input type="text" name="dbpass"><br>' . PHP_EOL
        . '   <input type="submit" name="submit">' . PHP_EOL
        . '</form>' . PHP_EOL;
    return $html;
}

?>
<!DOCTYPE html>

<html>
<head>
    <meta charset="UTF-8">
    <title></title>
</head>
<body>
<?php
echo $contents;
?>
</body>
</html>

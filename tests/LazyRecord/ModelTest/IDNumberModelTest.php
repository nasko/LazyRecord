<?php
class IDNumberModelTest extends \LazyRecord\ModelTestCase
{
    public $driver = 'sqlite';

    public function getModels()
    {
        return array('tests\\IDNumber');
    }

    public function testValidation() 
    {
        $record = new tests\IDNumber;
        $ret = $record->create(array( 'id_number' => 'A186679004' ));
        ok($ret->success);

        $ret = $record->create(array( 'id_number' => 'A222222222' ));
        not_ok($ret->success);

        $ret = $record->create(array( 'id_number' => 'A222' ));
        not_ok($ret->success);
    }
}


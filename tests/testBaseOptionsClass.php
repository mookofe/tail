<?php namespace Mookofe\Tail\Test;
 
use Mookofe\Tail\Tail;

/**
 * Test base option class
 *
 * @author Victor Cruz <cruzrosario@gmail.com> 
 */
class testBaseOptionsClass extends \PHPUnit_Framework_TestCase
{
    

    public function testAddWithoutOptions()
    {
        $this->assertTrue(true);
        //$tail->add('test-queue', "Message sent at :" . time());
    }
}
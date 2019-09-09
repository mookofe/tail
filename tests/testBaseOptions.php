<?php namespace Mookofe\Tail\Test;

use Mockery;
use Mookofe\Tail\BaseOptions;
use PHPUnit\Framework\TestCase;


/**
 * Test base option class
 *
 * @author Victor Cruz <cruzrosario@gmail.com>
 */
class testBaseOptions extends TestCase
{
    public function testValidateOptions()
    {
        $input = Mockery::mock('Illuminate\Config\Repository');

        $options = array('queue_name' => 'this_queue');
        $baseOptions = new BaseOptions($input);
        $result = $baseOptions->validateOptions($options);

        //Asserts
        $this->assertInstanceOf('Mookofe\Tail\BaseOptions', $result);

        Mockery::close();
    }

    /**
     * @expectedException     Mookofe\Tail\Exceptions\InvalidOptionException
     */
    public function testValidateOptionsInvalid()
    {
        $input = Mockery::mock('Illuminate\Config\Repository');

        $options = array('invalid_field' => 'this_is_invalid_field');
        $baseOptions = new BaseOptions($input);
        $result = $baseOptions->validateOptions($options);

        Mockery::close();
    }

    public function testSetOptions()
    {
        $input = Mockery::mock('Illuminate\Config\Repository');

        $options = array('queue_name' => 'this_queue');
        $baseOptions = new BaseOptions($input);
        $baseOptions->setOptions($options);

        //Assertss
        $this->assertObjectHasAttribute('queue_name', $baseOptions);
        $this->assertEquals($baseOptions->queue_name, $options['queue_name']);
        Mockery::close();
    }

    public function testBuildConnectionOptions()
    {

        $input = Mockery::mock('Illuminate\Config\Repository');
        //Mock Input object
        $input->shouldReceive('get')->once()->andReturn('just_to_return');
        $input->shouldReceive('get')->once()->andReturn(array());

        //Setup enviroment
        $baseOptions = new BaseOptions($input);
        $options = $baseOptions->buildConnectionOptions();

        //Asserts
        $this->assertInternalType('array', $options);
        $this->assertArrayHasKey('queue_name', $options);

        Mockery::close();
    }
}
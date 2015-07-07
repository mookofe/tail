<?php namespace Mookofe\Tail\Test;

use Mockery;
use Mookofe\Tail\BaseOptions;


/**
 * Test base option class
 *
 * @author Victor Cruz <cruzrosario@gmail.com> 
 */
class testBaseOptions extends \PHPUnit_Framework_TestCase
{

    protected $input;
    
    public function __construct()
    {
        $this->input = Mockery::mock('Illuminate\Config\Repository');
        
    }

    public function tearDown()
    {
        Mockery::close();
    }

    public function testValidateOptions()
    {
        $options = array('queue_name' => 'this_queue');
        $baseOptions = new BaseOptions($this->input);

        $result = $baseOptions->validateOptions($options);

        //Asserts
        $this->assertInstanceOf('Mookofe\Tail\BaseOptions', $result);
    }

    /**
     * @expectedException     Mookofe\Tail\Exceptions\InvalidOptionException
     */
    public function testValidateOptionsInvalid()
    {
        $options = array('invalid_field' => 'this_is_invalid_field');
        $baseOptions = new BaseOptions($this->input);

        $result = $baseOptions->validateOptions($options);        
    }

    public function testSetOptions()
    {
        $options = array('queue_name' => 'this_queue');
        $baseOptions = new BaseOptions($this->input);

        $baseOptions->setOptions($options);

        //Assertss        
        $this->assertObjectHasAttribute('queue_name', $baseOptions);
        $this->assertEquals($baseOptions->queue_name, $options['queue_name']);
    }

    public function testBuildConnectionOptions()
    {
        //Mock Input object
        $this->input->shouldReceive('get')->once()->andReturn('just_to_return');
        $this->input->shouldReceive('get')->once()->andReturn(array());
        
        //Setup enviroment
        $baseOptions = new BaseOptions($this->input);
        $options = $baseOptions->buildConnectionOptions();

        //Asserts
        $this->assertInternalType('array', $options);
        $this->assertArrayHasKey('queue_name', $options);
    }
}
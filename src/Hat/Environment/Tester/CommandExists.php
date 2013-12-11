<?php
namespace Hat\Environment\Tester;


use Hat\Environment\LimitedString;

class CommandExists extends Tester
{
    protected $defaults = array(
        'finder' => 'which',
        'sudo' => false
    );

    public function test()
    {
        //TODO [extract][cli][component] extract to CLI component
        $command = $this->get('command');
        $cmd = ($this->get('sudo')?'sudo ':'') . $this->get('finder') . ' ' . $command;
        $output = '';
        exec($cmd, $output, $return);

        $this->set('output', new LimitedString($output));

        return $return == 0;
    }
}

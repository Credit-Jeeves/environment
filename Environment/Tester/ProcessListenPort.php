<?php
namespace Environment\Tester;

use Environment\Tester;

class ProcessListenPort extends Tester
{
    protected $defaults = array(
        'process' => 'process name',
        'port' => null,
        'command' => 'sudo lsof -i -n',
    );

    public function test()
    {

        //TODO [extract][cli][component]
        $command = $this->get('command');
        $output = array();
        exec($command, $output, $return);

        if ($return == 0) {

            foreach ($output as $line) {
                if ($this->containsProcess($line) && $this->containsPort($line)) {
                    return true;
                }
            }

        }


        return false;
    }

    protected function containsProcess($line)
    {
        return !is_null($this->get('process')) && strpos($line, $this->get('process')) !== false;
    }


    protected function containsPort($line)
    {
        return !is_null($this->get('port')) && strpos($line, $this->get('port')) !== false;
    }


}

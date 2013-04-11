<?php
namespace Hat\Environment\Tests\Functional;

use Hat\Environment\Environment;
use Hat\Environment\Kit\Kit;
use Hat\Environment\Kit\Service;

class EnvironmentTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Environment
     */
    protected $environment;

    protected function setUp()
    {
        $libRoot = realpath(__DIR__ . '/../../../../..') . '/';
        require_once $libRoot . 'vendor/autoload.php';

        $configs = require $libRoot . 'src/Hat/Environment/Environment.config.php';
        $configs['default.profile.name'] = $libRoot .
            'src/Hat/Environment/Tests/Functional/Resourses/Fixtures/profile.ini';

        $configs['output'] = new Service(function (Kit $kit) {
            $output = new \Hat\Environment\Output\EnvironmentOutput($kit->get('request'));
            return $output;
        });

        $this->environment = new Environment(new Kit($configs));

        parent::setUp();
    }

    /**
     * @test
     */
    public function currentDefinitionMusRewriteParents()
    {
        $this->environment->__invoke();
    }
}

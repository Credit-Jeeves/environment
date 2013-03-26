<?php
namespace Hat\Environment\Handler;

use Hat\Environment\Profile;

use Hat\Environment\Loader\ProfileLoader;
use Hat\Environment\Register\ProfileRegister;

use Hat\Environment\State\State;

class ProfileHandler extends Handler
{

    /**
     * @var DefinitionHandler
     */
    protected $definition_handler;

    /**
     * @var \Hat\Environment\Loader\ProfileLoader
     */
    protected $loader;

    /**
     * @var \Hat\Environment\Register\ProfileRegister
     */
    protected $register;

    public function __construct(ProfileLoader $loader, ProfileRegister $register, DefinitionHandler $definition_handler)
    {
        $this->loader = $loader;
        $this->definition_handler = $definition_handler;
        $this->register = $register;
    }


    public function handlePath($path)
    {
        return $this->handle($this->loader->loadByPath($path));
    }

    public function supports($profile)
    {
        return $profile instanceof Profile;
    }

    protected function doHandle($profile)
    {
        return $this->handleProfile($profile);
    }

    protected function handleProfile(Profile $profile)
    {
        $this->register->register($profile);
        $this->handleDefinitions($profile);
    }

    protected function handleDefinitions(Profile $profile)
    {
        echo "\n";
        echo "[handle] ";
        echo $profile->getPath();
        echo "\n";
        echo "\n";

        $profile->getState()->setState(State::OK);

        $failed = 0;
        $passed = 0;
        foreach ($profile->getDefinitions() as $definition) {

            $definition->recompile();
            $this->definition_handler->handle($definition);

            if ($definition->getState()->isFail()) {
                $profile->getState()->setState(State::FAIL);
                $failed++;
            } else if($definition->getState()->isOk()){
                $passed++;
            }

        }

        echo "[{$profile->getState()->getState()}] total: failed {$failed}, passed {$passed}";
        echo "\n";
        echo "\n";

    }


}

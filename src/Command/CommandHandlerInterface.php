<?php
/**
 * Created by PhpStorm.
 * User: toby
 * Date: 08/10/15
 * Time: 23:26
 */

namespace CubicMushroom\Hexagonal\Command;

/**
 * Class CommandHandlerInterface
 *
 * @package CubicMushroom\Hexagonal
 */
interface CommandHandlerInterface
{
    /**
     * Processes the associated command
     *
     * @param CommandInterface $command
     *
     * @return void
     */
    public function handle(CommandInterface $command);
}
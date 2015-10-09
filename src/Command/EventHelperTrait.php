<?php
/**
 * Created by PhpStorm.
 * User: toby
 * Date: 07/10/15
 * Time: 21:22
 */

namespace CubicMushroom\Hexagonal\Command;

use CubicMushroom\Hexagonal\Event\EventInterface;
use League\Event\Emitter;

/**
 * Trait for assisting with event emissions
 *
 * @package CubicMushroom\Hexagonal
 */
trait EventHelperTrait
{
    // -----------------------------------------------------------------------------------------------------------------
    // Properties
    // -----------------------------------------------------------------------------------------------------------------

    /**
     * @var Emitter
     */
    protected $emitter;


    // -----------------------------------------------------------------------------------------------------------------
    // Setters
    // -----------------------------------------------------------------------------------------------------------------

    /**
     * @param Emitter $emitter
     */
    public function setEmitter(Emitter $emitter)
    {
        $this->emitter = $emitter;
    }


    /**
     * @param EventInterface $event
     */
    public function emit(EventInterface $event)
    {
        $this->emitter->emit($event);
    }
}
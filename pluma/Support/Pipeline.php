<?php

// ==========================================
// File: pluma/Support/Pipeline.php
// Description: Executes a sequence of pipes (middlewares or steps)
// ==========================================

namespace Pluma\Support;

class Pipeline
{
    /**
     * The value to send through the pipeline.
     *
     * @var mixed
     */
    protected mixed $passable;

    /**
     * The sequence of pipes (callables).
     *
     * @var array
     */
    protected array $pipes = [];

    /**
     * Set the initial passable object.
     *
     * @param mixed $passable
     * @return $this
     */
    public function send(mixed $passable): static
    {
        $this->passable = $passable;
        return $this;
    }

    /**
     * Define the sequence of pipes.
     *
     * @param array $pipes
     * @return $this
     */
    public function through(array $pipes): static
    {
        $this->pipes = $pipes;
        return $this;
    }

    /**
     * Run the pipeline with a destination closure.
     *
     * @param callable $destination
     * @return mixed
     */
    public function then(callable $destination): mixed
    {
        $pipeline = array_reduce(
            array_reverse($this->pipes),
            fn($stack, $pipe) => fn($passable) => $pipe($passable, $stack),
            $destination
        );

        return $pipeline($this->passable);
    }
}

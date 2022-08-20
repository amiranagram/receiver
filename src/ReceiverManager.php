<?php

namespace Receiver;

use Illuminate\Support\Arr;
use Illuminate\Support\Manager;
use InvalidArgumentException;
use Receiver\Contracts\Factory;
use Receiver\Providers\GithubProvider;
use Receiver\Providers\SlackProvider;

class ReceiverManager extends Manager implements Factory
{
    /**
     * Get a driver instance.
     *
     * @param string $driver
     * @return mixed
     */
    public function with(string $driver): mixed
    {
        return $this->driver($driver);
    }

    /**
     * Create an instance of the specified driver.
     */
    protected function createGithubDriver(): GithubProvider
    {
        $config = $this->config->get('services.github');

        return $this->buildProvider(
            GithubProvider::class,
            $config
        );
    }

    /**
     * Create an instance of the specified driver.
     */
    protected function createSlackDriver(): SlackProvider
    {
        $config = $this->config->get('services.slack');

        return $this->buildProvider(
            SlackProvider::class,
            $config
        );
    }

    /**
     * Build a webhook provider instance.
     *
     * @param string $provider
     * @param array $config
     * @return \Receiver\Providers\AbstractProvider
     */
    public function buildProvider(string $provider, array $config): Providers\AbstractProvider
    {
        return new $provider(
            Arr::get($config, 'webhook_secret')
        );
    }

    /**
     * Forget all the resolved driver instances.
     *
     * @return $this
     */
    public function forgetDrivers(): static
    {
        $this->drivers = [];

        return $this;
    }

    /**
     * Get the default driver name.
     *
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    public function getDefaultDriver()
    {
        throw new InvalidArgumentException('No Receiver driver was specified.');
    }
}
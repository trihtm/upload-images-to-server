<?php

include_once('EnvironmentDetector.php');

class Environment
{
	protected $currentEnv;

	/**
	 * Get or check the current application environment.
	 *
	 * @param  dynamic
	 * @return string
	 */
	public function environment()
	{
		if (count(func_get_args()) > 0)
		{
			return in_array($this->currentEnv, func_get_args());
		}
		else
		{
			return $this->currentEnv;
		}
	}

	public function detectEnvironment($envs)
	{
		$args = isset($_SERVER['argv']) ? $_SERVER['argv'] : null;

		$detector = new \Foundation\EnvironmentDetector();

		return $this->currentEnv = $detector->detect($envs, $args);
	}
}
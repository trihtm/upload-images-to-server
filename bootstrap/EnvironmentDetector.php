<?php namespace Foundation;

use Closure;

class EnvironmentDetector
{

	/**
	 * Detect the application's current environment.
	 *
	 * @param  array|string  $environments
	 * @param  array|null  $consoleArgs
	 * @return string
	 */
	public function detect($environments, $consoleArgs = null)
	{
		if ($consoleArgs)
		{
			return $this->detectConsoleEnvironment($environments, $consoleArgs);
		}
		else
		{
			return $this->detectWebEnvironment($environments);
		}
	}

	/**
	 * Set the application environment for a web request.
	 *
	 * @param  array|string  $environments
	 * @return string
	 */
	protected function detectWebEnvironment($environments)
	{
		// If the given environment is just a Closure, we will defer the environment check
		// to the Closure the developer has provided, which allows them to totally swap
		// the webs environment detection logic with their own custom Closure's code.
		if ($environments instanceof Closure)
		{
			return call_user_func($environments);
		}

		foreach ($environments as $environment => $hosts)
		{
			// To determine the current environment, we'll simply iterate through the possible
			// environments and look for the host that matches the host for this request we
			// are currently processing here, then return back these environment's names.
			foreach ((array) $hosts as $host)
			{
				if ($this->isMachine($host)) return $environment;
			}
		}

		return 'production';
	}

	/**
	 * Set the application environment from command-line arguments.
	 *
	 * @param  mixed   $environments
	 * @param  array  $args
	 * @return string
	 */
	protected function detectConsoleEnvironment($environments, array $args)
	{
		// First we will check if an environment argument was passed via console arguments
		// and if it was that automatically overrides as the environment. Otherwise, we
		// will check the environment as a "web" request like a typical HTTP request.
		if ( ! is_null($value = $this->getEnvironmentArgument($args)))
		{
			return head(array_slice(explode('=', $value), 1));
		}
		else
		{
			return $this->detectWebEnvironment($environments);
		}
	}

	/**
	 * Get the enviornment argument from the console.
	 *
	 * @param  array  $args
	 * @return string|null
	 */
	protected function getEnvironmentArgument(array $args)
	{
		return array_first($args, function($k, $v)
		{
			return starts_with($v, '--env');
		});
	}

	/**
	 * Determine if the name matches the machine name.
	 *
	 * @param  string  $name
	 * @return bool
	 */
	public function isMachine($name)
	{
		return static::str_is($name, gethostname());
	}

	/**
	 * Determine if a given string matches a given pattern.
	 *
	 * @param  string  $pattern
	 * @param  string  $value
	 * @return bool
	 */
	public static function str_is($pattern, $value)
	{
		if ($pattern == $value) return true;

		$pattern = preg_quote($pattern, '#');

		// Asterisks are translated into zero-or-more regular expression wildcards
		// to make it convenient to check if the strings starts with the given
		// pattern such as "library/*", making any string check convenient.
		$pattern = str_replace('\*', '.*', $pattern).'\z';

		return (bool) preg_match('#^'.$pattern.'#', $value);
	}

}
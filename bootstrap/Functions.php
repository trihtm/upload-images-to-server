<?php

/**
 * Return the given object. Useful for chaining.
 *
 * @param  mixed  $object
 * @return mixed
 */
function with($object)
{
	return $object;
}

/**
 * Dump the passed variables and end the script.
 *
 * @param  dynamic  mixed
 * @return void
 */
function dd()
{
	array_map(function($x) { var_dump($x); }, func_get_args()); die;
}
<?php

namespace Wizhi\Helper;

use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\CliDumper;

/**
 * Class Dumper
 */
class Dumper
{
	/**
	 * Dump a value with elegance.
	 *
	 * @param  mixed  $value
	 * @return void
	 */
	public function dump($value)
	{
		if (class_exists(CliDumper::class)) {
			$dumper = 'cli' === PHP_SAPI ? new CliDumper : new HtmlDumper;
			$dumper->dump((new VarCloner)->cloneVar($value));
		} else {
			var_dump($value);
		}
	}
}
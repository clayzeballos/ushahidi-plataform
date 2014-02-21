<?php

// Sanity check, install should only be checked from index.php
defined('SYSPATH') or exit('Install tests must be loaded from within index.php!');

$failed = FALSE;
$test_groups = array(
	'Preflight' => array(
		'PHP Version' => function( & $message)
		{
			$message = 'Ushahidi requires PHP 5.3.3 or newer, this is ' .  PHP_VERSION;
			return version_compare(PHP_VERSION, '5.3.3', '>=');
		},
		'Security' => function( & $message)
		{
			$check = array(
				'ctype' => 'ctype_digit',
				'filter' => 'filter_list',
				'hash' => 'hash',
				);

			foreach ($check as $extension => $function)
			{
				if ( ! function_exists($function)) {
					$message = "The [{$missing}] PHP extension is required for security";
					return FALSE;
				}
			}
			$message = "PHP has basic security requirements";
			return TRUE;
		},
		'i18n' => function( & $message)
		{
			if ( ! @preg_match('/^.$/u', 'ñ'))
			{
				$missing = 'PCRE UTF-8';
			}
			elseif ( ! @preg_match('/^\pL$/u', 'ñ'))
			{
				$missing = 'PCRE Unicode';
			}
			elseif ( ! extension_loaded('iconv'))
			{
				$missing = 'iconv';
			}
			elseif (extension_loaded('mbstring') AND (ini_get('mbstring.func_overload') & MB_OVERLOAD_STRING))
			{
				$missing = 'mbstring without overloading';
			}

			if (isset($missing))
			{
				$message = "PHP requires [{$missing}] for proper international support";
			}
			else
			{
				$message = "PHP has UTF-8 and Unicode support enabled";
			}

			return ! isset($missing);
		},
		'System' => function( & $message)
		{
			$message = "The [system] directory has the required dependencies";
			return (is_dir(SYSPATH) AND is_file(SYSPATH.'classes/Kohana'.EXT));
		},
		'Application' => function( & $message)
		{
			$message = "The [application] directory contains the Ushahidi application";
			return (is_dir(APPPATH) AND is_file(APPPATH.'bootstrap'.EXT));
		},
		'Caching' => function( & $message)
		{
			$message = "The application [cache] directory must be writable";
			return (is_dir(APPPATH) AND is_dir(APPPATH.'cache') AND is_writable(APPPATH.'cache'));
		},
		'Logging' => function( & $message)
		{
			$message = "The application [logs] directory must be writable";
			return (is_dir(APPPATH) AND is_dir(APPPATH.'logs') AND is_writable(APPPATH.'logs'));
		},
	),
	'Installer' => array(
		'Bootstrap' => function( & $message)
		{
			// Bootstrap the application
			require APPPATH.'bootstrap'.EXT;
			return TRUE;
		},
		'Database' => function ( & $message)
		{
			$config = Kohana::$config->load('database')->default;
			return ! empty($config);
		},
	),
);

$tests_total = 0;
$tests_done = 0;

foreach ($test_groups as $a) {
	foreach ($a as $b) {
		$tests_total += count($b);
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Ushahidi Installation</title>

	<style type="text/css">
	body { width: 42em; margin: 0 auto; font-family: sans-serif; background: #fff; font-size: 1em; }
	h1 { letter-spacing: -0.04em; }
	code { font-family: monaco, monospace; }
	ol { display: block; padding: 1em 0; margin: 0 0 1em; text-align: justify; }
	ol li { display: inline-block; padding: 0.3em 0.5em; border-radius: 0.3em; margin: 0.3em 0.1em; cursor: default; color: #fff; }
	ol li.pass { background: #191; }
	ol li.fail { background: #911; }
	#results { padding: 0.8em; padding-left: 4.4em; background: #eee; font-size: 1.5em; }
	#results span { display: block; float: left; margin-left: -1.7em; margin-top: -0.6em; padding: 0.6em 0.5em; padding-left: 0; font-size: 2em; border-right: solid 2px #fff; }
	#results.pass span { color: #191; }
	#results.fail span { color: #911; }
	#powerTip {
		cursor: default;
		background-color: #333;
		background-color: rgba(0, 0, 0, 0.8);
		border-radius: 6px;
		color: #fff;
		display: none;
		padding: 10px;
		position: absolute;
		white-space: nowrap;
		z-index: 2147483647;
		font-size: 0.8em;
	}
	#powerTip:before {
		content: "";
		position: absolute;
	}
	#powerTip.s:before {
		border-right: 5px solid transparent;
		border-left: 5px solid transparent;
		left: 50%;
		margin-left: -5px;
		border-bottom: 10px solid #333;
		border-bottom: 10px solid rgba(0, 0, 0, 0.8);
		top: -10px;
	}
	#powerTip.sw-alt:before, #powerTip.se-alt:before {
		border-top: none;
		border-left: 5px solid transparent;
		border-right: 5px solid transparent;
		left: 10px;
		border-bottom: 10px solid #333;
		border-bottom: 10px solid rgba(0, 0, 0, 0.8);
		bottom: auto;
		top: -10px;
	}
	#powerTip.se-alt:before {
		left: auto;
		right: 10px;
	}
	</style>

</head>
<body>

	<h1>Ushahidi Installer</h1>
	<p>
		The following tests have been run to determine if <a href="http://ushahidi.com/">Ushahidi</a> will work on this server.
		If any of the tests have failed, consult the <a href="https://wiki.ushahidi.com/display/WIKI/Installing+Ushahidi+3.x">installation guide</a> for additional assistance. 
	</p>


	<ol>
		<?php
		foreach ($test_groups as $group => $tests) {
			foreach ($tests as $name => $test) {
				$success = $test($message);
				if ( ! $success)
				{
					$failed = TRUE;
				}
				$tests_done++;
				?>
				<li class="<?php echo $success ? 'pass' : 'fail'; ?>" title="<?php echo $message ?>"
					><?php echo $name; ?></li>
				<?php
			}
			if ($failed)
			{
				break; // do not check any more groups
			}
		}
		if ($failed AND ($tests_total - $tests_done) > 0): ?>
			<li class="fail" title="Additional checks skipped due to failures"
				><?php echo ($tests_total - $tests_done) ?> Tests Remaining</li>
		<?php endif ?>
	</ol>

	<?php if ($failed): ?>
		<p id="results" class="fail"><span>✘</span> Ushahidi may not work correctly with your environment.</p>
	<?php else: ?>
		<p id="results" class="pass"><span>✔</span> Ushahidi is ready!<br/>
			<small>Remove the <code>install<?php echo EXT ?></code> file now.</small></p>
	<?php endif ?>

	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-powertip/1.2.0/jquery.powertip.min.js"></script>
	<script type="text/javascript">
	$(function() {
		$('li[title]').powerTip({
			placement: 'sw-alt',
			smartPlacement: true
			});
	});
	</script>

</body>
</html>

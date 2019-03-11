<?php require '../vendor/autoload.php';

use Intellex\Stopwatch\Stopwatch;

// Development tools
\Intellex\Debugger\IncidentHandler::register();
function debug($data) {
	\Intellex\Debugger\VarDump::from($data, 1);
}

// Should be 0
Stopwatch::start('Zero');
Stopwatch::stop('Zero');

// Something to measure
Stopwatch::start('Init');
usleep(35000);
$measurement = Stopwatch::stop('Init'); // Returns an instance of Measurement

// Available options
$measurement->getStart();    // The start time of the measurement, in milliseconds
$measurement->getEnd();      // The end time of the measurement in milliseconds
$measurement->getTime();     // The total time elapsed in milliseconds

Stopwatch::start('Total');
Stopwatch::start('Init');
usleep(9000);
Stopwatch::stop('Init');
Stopwatch::start('Database');
Stopwatch::start('Connection');
usleep(76000);
Stopwatch::stop('Connection');
Stopwatch::start('Read');
usleep(12000);
Stopwatch::start('Cache');
usleep(3000);
Stopwatch::stop('Cache');
Stopwatch::stop('Read');
Stopwatch::stop('Database');
$measurement = Stopwatch::stop('Total');

// The total time elapsed in 'Total', in milliseconds
$measurement->getTime();

// The list of direct children measurements ('Database' and 'Rendering'), with same API as this measurement
$subMeasurements = $measurement->getChildren();

// Measurements
echo '<pre>';
$measurements = Stopwatch::getMeasurements();
foreach ($measurements as $measurement) {
	echo $measurement->report();
}
echo '</pre>';

// Aggregated
$loops = 10;
while ($loops--) {
	Stopwatch::startAggregate('loop');
	usleep(3000);
	Stopwatch::pauseAggregate('loop');
}

// Looped measurements
$measurements = Stopwatch::getAggregatedMeasurements();
foreach ($measurements as $measurement) {
	echo $measurement->getName() . ': ' . $measurement->getTotal() . 'ms, in ' . $measurement->getCount() . ' passes' . '<br />';
}

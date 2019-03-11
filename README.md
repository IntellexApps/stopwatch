# Lightweight PHP time measurement

Useful for debugging slow scripts and optimizing frameworks.

* Simple __start__ / __stop__ stopwatch
* Supports __nested__ measurements 
* __Aggregated__ measurements for __repeating__ functions
* Plain text __report__


Exception handling
--------------------

The Stopwatch will throw an exception on any anomaly. However, by default these exceptions are silently ignored.

In order to set a custom handler use:
<pre>
StopwatchException::setExceptionHandler(StopwatchExceptionHandling $handler);
</pre>

For complete list of exceptions, see <code>/src/Exception</code> directory.


API
--------------------

#### Simple usage

Use the pair of methods <code>Stopwatch::start(string $name)</code> and <code>Stopwatch::stop(string $name)</code> to measure elapsed time.<Br />
The <code>string $name</code> is used to name and identify the measurement and it must match across the two methods.

<code>Stopwatch::stop(string $name)</code> returns an instance <code>Measurement</code>, which holds the information about the measurement.

Example:
<pre>
Stopwatch::start('Init');
// ...
$measurement = Stopwatch::stop('Init'); // Returns an instance of Measurement

// Available options
$measurement->getStart();    // The start time of the measurement, in milliseconds 
$measurement->getEnd();      // The end time of the measurement in milliseconds 
$measurement->getTime();     // The total time elapsed in milliseconds 
</pre> 

#### Nested

Nested measurements allow you to get more details where the time is spent, but also to easily know the total time spent. Level of nesting is not limited.

Example:
<pre>
Stopwatch::start('Total');
    // ...
    Stopwatch::start('Init');
        // ...
    Stopwatch::stop('Init');
    
    Stopwatch::start('Database');
        Stopwatch::start('Connection');
            // ...
        Stopwatch::stop('Connection');
        
        Stopwatch::start('Read');
            // ...
            Stopwatch::start('Cache');
            	// ...
            Stopwatch::stop('Cache');
        Stopwatch::stop('Read');
    Stopwatch::stop('Database');
$measurement = Stopwatch::stop('Total');

// The total time elapsed in 'Total', in milliseconds
$measurement->getTime();                       

// The list of direct children measurements ('Database' and 'Rendering'), with same API as this measurement
$subMeasurements = $measurement->getChildren();
</pre>

#### Aggregated

If a certain function is called multiple times, measure the total time.

<pre>
$loops = 10;
while ($loops--) {
	Stopwatch::startAggregate('loop');
		// ...
	Stopwatch::pauseAggregate('loop');
}

// Get the instance of <code>AggregateMeasurment</code>
$aggregateMeasurement = Stopwatch::getAggregatedMeasurements()['loop'];

$aggregateMeasurement->getTotal()	// The total elapsed time, in milliseconds
$aggregateMeasurement->getCount()	// The number of loops
</pre>

#### Print summary

Print the summary of all measurements as plain text: <code>$measurement->report()</code>.
<pre>
&lt;pre&gt;&lt;?php echo $measurement->report() ?&gt;&lt;/pre&gt;
</pre>

From the nested example above,  will produce:
<pre>
Total: 100
├───Init: 9
└───Database: 91
	├───Connection: 76
	└───Read: 15
		└───Cache: 3
</pre>


TODO
--------------------
1. Tests.
2. Improve the print summary.


Licence
--------------------
MIT License

Copyright (c) 2019 Intellex

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.


Credits
--------------------
Script has been written by the [Intellex](https://intellex.rs/en) team.

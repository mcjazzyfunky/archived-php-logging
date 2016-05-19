# php-logging

A super simple yet powerful logging facade for PHP

# Introduction

Logging is a cross-cutting concern that is heavily used in almost every
serious application.<br>
An important topic that has to be dealt with at the beginning of a new software
project is the question which logging framework shall be applied.<br>
You may think that in case your initial requirements regarding logging
are not very demanding, you could write the logging functionality completely
on your own.<br>
But that would not be a good idea. Because there's a pretty good chance that in
in future, after your application  has been running successfully for a while,
there will eventually be the need to split up your former single log file into
several different ones depending on different aspects of your application
(for example per module).<br>
Or maybe there will be a change request to send an emergency email on certain
log entries.<br>
Of course you can implement all these changes on your own, but luckily some
very smart guys have already done this for you, so you should better prefer
to use their libraries instead of writing everything from scratch.<br>

Some great PHP logging frameworks are for example:

* [log4php](https://logging.apache.org/log4php)
* [monolog](https://github.com/Seldaek/monolog)
* [Zend\Log](http://framework.zend.com/manual/current/en/modules/zend.log.overview.html)
* etc.

Now as you have decided to use an existing logging framework, which one shall
you use?<br>
As often the answer is: *It depends.*<br>
In case that you will write a whole application it may be possible to depend
on one special logging framework.<br>
But in case that you write a library that will not be possible, because you
never know which logging framework is used in the applications that will use
your library.<br>
A way out of the dilemma is not to use the logging framework directly but to use
a simple facade to delegate the log request to an underlying and exchangeable
logging framework (in the Java world a very commonly used logging facade is
["Simple logging facade for Java (slf4j)"](http://www.slf4j.org)).<br>
A PHP de facto standard logger interface is the
[PSR-3 Logger Interface](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md)
approved by the [PHP Framework Interop Group](https://en.wikipedia.org/wiki/PHP_Standard_Recommendation).<br>
Maybe using this quasi standard should be your first choice!<br>
Nevertheless the author for "php-logging" is of the opinion, that the API of
PSR-3 is a bit suboptimal (for example the PSR-3 logger interface does not
provide methods to find out whether logging is activated for a certain domain,
also you cannot pass the throwables/exceptions which are the cause of the log
entry directly as method argument but have to pass them as part of the
so-called "context" array etc.).<br>
That's why the "php-logging" project has been started.

# Motivation

The purpose of "php-logging" is to provide an as simple as possible logging
facade that is completely independent of the actually used logging framework
and also completely independent of the underlying logging logic of the
application.<br>
The key feature is to provide a simple API for the logging itself plus the
possibility to adapt the logging facade easily to the underlying logging
system or logic.<br>
Unlinke other logging facade libraries, "php-logging" does not
(and never will) provide any adapters to well-known logging frameworks, it just
provides some most simple preexisting adapter to write log entries to one single
log file/stream or to simplify customization.<br>
There are no configuration files. The idea behind this is that it is often far
easier to do the initialization and adaption of the logging facade directly
by PHP code.<br>
In the best case (maybe for 75% of all applications) you just have to write
four lines of PHP code to make "php-logging" run propertly in your
application.<br>
If having only one log file is fine for you but you would like a different
text format for the log entries or just pass the log parameters directly to
the logging system (maybe 15% of all cases) you have to write
those four lines plus one closure.<br>
For all other (maybe 5%) cases you have to implement two interfaces to
make the log entries be processed the way you like.

Supported log levels:

* TRACE
* DEBUG
* INFO
* NOTICE
* WARN
* ERROR
* CRITICAL
* ALERT
* EMERGENCY


# Deployment

Just add the "php-logging" folder somewhere to your project
("composer" support is currently not available).<br>
Then include the PHP file "include.php" which is to be found in the root
folder of  "php-logging" (all classe and interface files will be included
automatically as "php-logging" uses PHP's autoload functionality).

# Initialization of "php-logging"

"php-logging" is actually completely working just by including the above
mentioned "incude.php" file.<br>
But by default it uses the so-call NullLoggerAdapter which just consumes all
log requests without really doing anything.

You mainly have to deal with to classes or intefaces:

*  interface *Log*:

  Needed to send logging requests.

* class *Logger*:

  Needed to configure the logging logic, setting the default log threshold
  and providing instances of interface
  *Log*.

Remark: A class "MDA" will follow later versions of "php-logging" to handle
"mapped diagnostic context" to provide a possibility to more ore less
implicitly add additional information to the log entries (for example session
ids and user login names etc).

To activate a file logger you have to add the following lines (the placeholder
{date} will be replaced automatically with the current date in ISO format
- e.g. 2016-05):

```php
$logFile = __DIR__ . '/path/to/log/folder/app-{date}.log';
Logger::setAdapter(new FileLogAdapter($logFile));
Logger::setDefaultThreshold(Log::INFO);
```

If you like to log out to STDOUT (for example for test purpose) then you have to call:

```php
Logger::setLogAdapter(new StreamLogAdapter(STDOUT));
Logger::setDefaultThreshold(Log::Info);
```
Instantiate/get a log by a certain name (normally by class name):

```php
$this->log = Logger::getLog($this);
Logger::getLog(get_class($this));
```
Other options are for example:

```php
$this->log = Logger::getLog(self::class);
// same as Logger::getLog(__CLASS__);
```
Also possible:

```php
$this->log = Logger::getLog('someCustomLogName');
```

Sending log requests:

```php
$this->log->trance('just to trace');
$this->log->debug('to debug something');
$this->log->info('to send a minor important information');
$this->log->notice('to send a major important notice');
$this->log->warn('something went wrong')
$this->log->error('Ooops, something went really wrong');
$this->log->critical('Oh no, that may cause some serious trouble');
$this->log->alert('Oh my goodness, something caused some really serious trouble');
$this->log->emergency('Run for your lives! Core melt accident in sector 7G!!!');
```
Placeholders for log messages:

```php
$this->log->error('DB error: {0}', $exception->getMessage());

$this->log->error('DB error ({code}): {errMsg}, [
    'code' => $exception->getCode()
    'errMsg' => $exception->getMessage()
]);

```
Logging stack trace:

```php
$this->log->error('DB error: {0}', $exception->getMessage(), $exception);
```
Logging extra data:

```php
$this->log->emergency('Core melt accident!!!', null, $exception, {
    'location' => 'Sector 7G'
});
```

Customizing log format/message (for more details see API documentation):

```php
Logger::setLogAdapter(new FileLogAdapter($logFile, function ($logParams) {
    // return output string to be used for logging
});
```

For a more sophisticated log customization, e.g.

```php
Logger::setLogAdapter(new CustomLogAdapter(function ($logParams) {
    // do whatever you thing is necessary to perform the logging properly
});
```

For the ultimate customization you may provide your own implementation of
the interfaces *Log* and *LogAdapter* (see API documentation for detials).

That's it ... have fun ... ;-)

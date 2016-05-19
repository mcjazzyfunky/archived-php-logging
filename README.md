# php-logging

A super simple yet powerful logging facade for PHP

# Introduction

Logging is a cross-cutting concern that is heavily used in almost every
serious application.<br>
An important topic that has to be dealt with at the beginning of a new software
project is the question which logging framework shall be applied.<br>
You may think that in case that your initial requirements regarding logging
are not very demanding, you could write the logging functionality completely
on your own.<br>
But that would not be a good idea. Because there's a good chance that in future
after your application  has been running successfully for a while there will
eventually be the need to split up your former single log file into several
different ones depending on different aspects of your application
(for example per module).<br>
Or maybe there will be a change request to send an emergency email on certain
special log entries.<br>
Of course you can implement all these changes on your own, but quite a lot of
very smart guys have already done this for you, so you should better prefer
to use their libraries instead of writing everything on your own.<br>

Some great PHP logging frameworks are for example:

* [log4php](https://logging.apache.org/log4php)
* [monolog](https://github.com/Seldaek/monolog)
* [Zend\Log](http://framework.zend.com/manual/current/en/modules/zend.log.overview.html)
* etc.

Now as we have decided to use a existing logging framework, which one shall we
use?<br>
As often the answer is: It depends.<br>
In case that you will write a whole application it may be possible to depend
on one special logging framework.<br>
In case that you write a library it will not be possible, because you never know
which logging framework is used in the applications that will use your
library.<br>
A way out of the dilemma is not to use one logging framework directly but to use
a simple facade to delegate the log request to a underlying and exchangeable
logging framework (in the Java world a very commonly used logging facade is
[slf4j](http://www.slf4j.org)).<br>
A PHP de facto standard logger interface is the
[PSR-3 Logger Interface](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md)
approved by the [PHP Framework Interop Group](https://en.wikipedia.org/wiki/PHP_Standard_Recommendation).<br>
Maybe using this quasi standard should be your first choice.<br>
Nevertheless the author for "php-logging" is of the opinion, that the API of
PSR-3 could be approved (for example the PSR-3 logger interface does not
provide methods to find out whether logging is activated for a certain log
in a certain domain, also you cannot pass the throwables/exceptions which
are the cause of the log entry directly as method argument but have to pass
them as part of the so-called "context" array etc.).<br>
That's why the "php-logging" project has been started.

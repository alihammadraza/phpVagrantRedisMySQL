This project uses git, Vagrant, PHP, MySQL, PHPUnit & VirtualBox, make sure vagrant, git and virtualbox are installed in your system before cloning this.

The provisioning facility of vagrant provides a box with the necessary software to get the code running.


You can clone/run the entire system by doing:

* `git clone <this repository url>`
* `vagrant up --provision`
* run checks against index.php, stats.php and database.


The code architecture is based on service/entity model where entities act like validating data
types and are consumed inside services which perform various functions.


## SMS Storage API / index.php & redis.php

I have tried to model an SMS subscribe service. The industry term for such SMS'es is MO, which 
stands for Mobile Originated. A Client can subscribe by simply sending a subscription request 
via SMS to a so-called shortcode; the Client sends an SMS with subscription request 'ON LEARNING'
to shortcode 32788 to subscribe to mobile learning services.

An example of a call through to the MO API would look like this:
http://localhost/index.php?msisdn=60123456789&operatorid=3&shortcodeid=8&text=ON+LEARNING

index.php uses moService object to store these passed GET values inside MySQL db after performing necessary validation.

redis.php used redisService object to store these passed GET values inside Redis-Server but without performing any validation.

The job at hand required fast throughput for processing values, my idea is that we
could just save the entire $_REQUEST array without validating any data at first (thereby
speeding up page execution times), I applied this approach to redis.php . Later we can
process the stored values in whatever way we like using a cron script at our own pace.
Although in index.php the classes used perform validation on input data when page is loaded
but the same approach like the one in redis where data is saved first and cron used later
can be applied too in order to improve performance.

In order to try and speed up the application I have turned on php zend opcache which
resulted in a sizable improvement in page load times on apache bench. But I think
more can be done here if we were to use nginx instead of apache2 or put haproxy at top
and only channel index.php requests to nginx with the other going through apache. In
my experience nginx performs even better than apache with mpm_worker. Changing  settings
in mysql config file for innodb related values can also bring improvement.


## Stats.php

stats.php file uses the moService object to provide provide with relevant performance statistics of the MO API.
It providers 
	1) The number of messages that have been processed in the last 15 minutes
	2) The time taken for processing last 10,000 messages i.e. start and end time 


## Bonus 1 script

In order to better integrate with the monitoring tools and show how many MO's have been received but
not yet processed the bonus1 *command line tool* when executed prints out a single integer: the number of 
MO's received but not yet processed.

## Bonus 2 script

The bonus2 *command line tool* when executed clears (removes) all those MO's that have been received
but have not yet processed .


You can use the bonus1 and bonus2 scripts using absolute paths or by concatenating ./
if executed from within the 'web' folder in shell. A message's status is deduced as being
processed or unprocessed based on the value in the newly introduced column of
process_status in table `mo` of mysql db samtt .


##PHPUnit tests
The unit tests are not very exhaustive but should be enough to demonstrate my grip on
the subject.

##Summarizing everything
I believe there are several ways to improve performance even further and besides performance 
is very relative e.g. performance on servers installed with ssd will of course be better,
however I agree that one should try and do as much as possible in removing any bottlenecks
which can kill even the most powerful of machines. In my personal experience php apc cache 
outperforms redis but yes it cannot be deployed in a cluster environment. So there are various
trade-offs with different technologies.



## IMPORTANT
*************************************************************************************
Because of hardware limitations, since my system did not support vt-x ,
I couldn't run the 64 bit guest os in virtualbox. Consequently I Had to use 32 bit ubuntu
but I ran into another problem, apparently there is a bug in virtualbox's new version as
mentioned in
http://stackoverflow.com/questions/31739755/vagrant-up-fails-to-start-network-interface-on-cloud-images
I had to therefore use this virtualbox version 4.3.28 .
Please take notice of this scenario, if the machine doesn't boot up for you then it’s very
likely an issue with your virtualbox but yes maybe switching to 64 bits might just work out
fine for you though I am unable to test or confirm that.
*************************************************************************************
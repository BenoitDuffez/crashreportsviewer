Android crash reports viewer
============================
This project is an ACRA PHP Frontend. ACRA is the Application Crash Reporting tool for Android. Check <https://github.com/ACRA/acra>
The aim of it is to have a rather simple setup: PHP and MySQL.
This should work pretty nicely with any version.
Any contribution welcome. I have very little time to spend on this project. I do use it for my production apps, so it should be useable as long as you don't want a nice UI ;)
See screenshots below.

Setup
=====

On the server
-------------

  * This application has to be installed on a Apache/PHP/*SQL machine configuration.
  * You should create a `config.php` file that indicates where is the SQL server. 
  * PHP 5.5.0 or 5.6.0 is required. Higher versions do not support MySQL, only MySQLi

Setup instructions:

	$ cd ~
	$ git clone https://github.com/BenoitDuffez/crashreportsviewer.git
	$ cd crashreportsviewer

Edit `config.php` file as follows:

	<?php
	$mysql_server = 'mysql.server.com'; // usually it's simply localhost
	$mysql_user = 'username';
	$mysql_password = 'password';
	$mysql_db = 'db_name'; 

Make Apache (or your webserver) point to `~/crashreportsviewer/www`.
Open up your web browser to `http(s)://your.server.tld/path/to/www/`, which should indicate a message that the database is not installed. Simply click the link, and go back to `http(s)://your.server.tld/path/to/www/`.

The database is now ready to receive crash reports. Configure your client (and make it crash!) to see if this is working for you.


On the client
-------------

ACRA should sent reports to `http://server.tld/path/to/submit.php`. Example annotation of your Android `Application` class:

	@ReportsCrashes(formKey = "", // will not be used
					formUri = "http://yourserver.com/path/to/submit.php",
					formUriBasicAuthLogin = "yourlogin", // optional
					formUriBasicAuthPassword = "y0uRpa$$w0rd", // optional
					mode = ReportingInteractionMode.TOAST,
					resToastText = R.string.crash_toast_text)
	public class MyApplication extends Application {
	...
	}


Usage
=====

Pages
-----

  * `/` or `/your.android.package/`: dashboard of the crashes of your applications (filtered by Android package name, if mentioned in the URL)
  * `/reports/`: view a all reports, grouped by `issue_id`
  * `/issue/<issue_id>/`: view a single report, identified by its `issue_id`

  Image: Example of the dashboard  
![Dashboard overview](https://raw.github.com/BenoitDuffez/crashreportsviewer/master/dashboard.png)


  Image: Example of an issue report
![Issue report](https://raw.github.com/BenoitDuffez/crashreportsviewer/master/issue.png)


Features
--------

  * `issue_id`: this is an md5sum of the exceptions and where they occured. This should give a unique identifier for the crash, and all crashes caused by the same issue (same exceptions at the same files/lines) should share the same `issue_id`.
  * You can filter by package name: `http://server.tld/com.yourcompany.yourproduct/reports.php` will display all information regarding `"com.yourcompany.yourproduct"` package only.
  * Wildcards in package name are supported: `http://server.tld/com.yourcompany.*/reports.php` will work.

License
=======

    Copyright 2013 Benoit Duffez

    Licensed under the Apache License, Version 2.0 (the "License");
    you may not use this file except in compliance with the License.
    You may obtain a copy of the License at

       http://www.apache.org/licenses/LICENSE-2.0

    Unless required by applicable law or agreed to in writing, software
    distributed under the License is distributed on an "AS IS" BASIS,
    WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
    See the License for the specific language governing permissions and
    limitations under the License.



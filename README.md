Android crash reports viewer
============================
Foreword: I am new to GitHub. Please advise if there's something wrong.

This project is an ACRA PHP Frontend. ACRA is the Application Crash Reporting tool for Android. Check <https://github.com/ACRA/acra>

Setup
=====

On the server
-------------

  * This application has to be installed on a Apache/PHP/*SQL machine configuration.
  * You should create a `config.php` file that indicates where is the SQL server. 

Example `config.php` file:

	<?php
	$mysql_server = 'mysql.server.com'; // usually it's simply localhost
	$mysql_user = 'username';
	$mysql_password = 'password';
	$mysql_db = 'db_name'; 


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


Usage
=====

Pages
-----

  * `index.php` : dashboard of the crashes of your applications
  * `report.php` : view a single report, identified by its `issue_id`
  * `reports.php` : view all reports, grouped by `issue_id`

  Image: Example of the dashboard  
![Dashboard overview](https://raw.github.com/BenoitDuffez/crashreportsviewer/master/dashboard.png)

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



Android crash reports viewer
============================
Foreword: I am new to GitHub. Please advise if there's something wrong.

This project is an ACRA PHP Frontend. ACRA is the Application Crash Reporting tool for Android. Check <http://code.google.com/p/acra/>

Setup
=====

On the server
-------------

  * This application has to be installed on a Apache/PHP/*SQL machine configuration.
  * MySQL server connection has to be configured in `./www/mysql.php`
  * Table should already be created.

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
![Dashboard overview](https://github.com/BicouQ/crashreportsviewer/raw/master/dashboard.png)

Features
--------

  * `issue_id`: this is an md5sum of the exceptions and where they occured. This should give a unique identifier for the crash, and all crashes caused by the same issue (same exceptions at the same files/lines) should share the same `issue_id`.
  * You can filter by package name: `http://server.tld/com.yourcompany.yourproduct/reports.php` will display all information regarding `"com.yourcompany.yourproduct"` package only.
  * Wildcards in package name are supported: `http://server.tld/com.yourcompany.*/reports.php` will work.



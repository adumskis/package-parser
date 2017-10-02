# Package parser
[Package Parser DEMO](http://package-parser.dumskis.lt/)
Small application that can parse gzip files feed and display data from them in line chart.

## What was used
1. Laravel 5.5 framework
2. PHP 7.0
3. MySQL 5.7
4. Supervisor
5. Ubuntu 16.04
6. LAMP

## How it works
It’s possible that amount of packages can be quite big, so I decided to use jobs and queue to extract and parse packages. To tell more details I’ll split this application into four stages.

### Stage 1: Uploading packages
To handle packages upload I used simple HTML form with one field that allows upload multiple files (`FeedController.php`). Files are validated by mime type of `application/x-gzip` and stored at server.
After files uploaded Feed object is created with status „in_queue“. Files are checked if they are valid, then created Package object for each of them and jobs (`ExtractPackageFile.php`) to extract gzips are put on queue;

### Stage 2: Extract package file
Before extracting package job changes Feed status to „extracting“.  File is opened with `gzopen()` method, it allows read file in stream. That helps when files are really big and they could take big part of memory. After files are extracted they are saved in another directory, original file is deleted and job (`ParsePackageXml.php`) to parse them is dispatched.

### Stage 3: Extract package file
To parse XML files, `XMLReader` class is used because it also helps prevent from taking too much memory. At the moment from file is taken only few values and saved in database. 

### Stage 4: Display data
User can access only feeds that has status „done“. Inside user can find line chart with data from packages by units of machines.
If that would be real life task I would think about queries cache.

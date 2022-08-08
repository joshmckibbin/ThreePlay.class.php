# ThreePlay.class.php
A simple PHP Class for interacting with the 3Play Media API (https://3playmedia.com)

## Basic Setup
```
// Include the Class
require_once 'ThreePlay.class.php';

// Set the API key provided by 3play
$apikey = 'xxxxxxxxxxxxxx';

// Initialize a new instance of the Class
$threeplay = new ThreePlay($apikey);
```

## Sample Requests
### Check the Status of a Transcript
```
$file_id = 0000000
$threeplay->status($file_id);
```

### Download a Transcript File
```
$file_id = 0000000; // Replace with the ID of a transcript file
$threeplay->download($file_id);
```

### Create a Batch and order a transcript
```
// Create the Batch
$batch_name = 'New Batch';
$batch_id = $threeplay->createBatch($batch_name);

// Order the Transcript
$file = 'path/to/file.mp4';
$threeplay->order($file, 'Name of Video', $batch_id);
```

More info about the API: [https://docs.3playmedia.com/apiv3/overview](https://docs.3playmedia.com/apiv3/overview)

# ThreePlay.class.php
A simple PHP Class for interacting with the 3Play Media API (https://3playmedia.com)

## Basic Usage
```
// Include the Class
require_once 'ThreePlay.class.php';

// Set the API key provided by 3play
$apikey = 'xxxxxxxxxxxxxx';

// Initialize a new instance of the Class
$threeplay = new ThreePlay($apikey);

// Download a Transcript File
$file_id = '0000000'; // Replace with the ID of a transcript file
$threeplay->download($file_id);
```

For more info about the API: [https://docs.3playmedia.com/apiv3/overview](https://docs.3playmedia.com/apiv3/overview)

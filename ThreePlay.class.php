<?php
namespace FoxODL\ThreePlay;

class ThreePlay {

    const SERVER = 'https://api.3playmedia.com/v3';

    private $apikey = null;


    /**
     * Create the 3Play Library
     * @param string $apikey The 3Play API Key
     */
    public function __construct(string $apikey) {
        $this->apikey = $apikey;
    }


    /**
     * Make a CURL request
     * @param string $endpoint The API endpoint
     * @param array $params The parameters to be sent
     * @param array $options Additional CURL options
     * @param string $method The request method (GET, POST)
     * @return array
     */
    public function request(string $endpoint, $params = array(), $options = array(), string $method = 'GET') {

        $init_params = array(
            'api_key' => $this->apikey
        );

        $init_opts = array(
            CURLOPT_URL => self::SERVER . $endpoint . '?' . http_build_query($init_params + $params), 
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FAILONERROR => true,
            CURLOPT_CUSTOMREQUEST => $method,
        );

        $ch = curl_init();
        curl_setopt_array($ch, $init_opts + $options);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
        }

        curl_close($ch);

        if (isset($error_msg)) {
            return 'Error: ' . $error_msg;
        } else {
            return json_decode($response, true);
        }

    }


    /**
     * Upload a video file for transcription
     * @param string $filename The filename, including the path
     * @param string $name The name of the transcript
     * @param int|null $batch_id The ID of the batch/folder to place the transcript in
     * @param int $language_id The ID of the language used in the transcript
     */
    public function upload(string $filename, string $name, int $batch_id = null, int $language_id = 1) {

        $endpoint = '/files';

        $cFile = ( function_exists("curl_file_create") ? curl_file_create($filename) : "@" . realpath($filename) );

        $data = array(
            'name' => $name,
            'language_id' => $language_id,
            'source_file' => $cFile,
            'batch_id' => ( $batch_id ? $batch_id : 4681 )
        );

        $options = array(
            CURLOPT_POSTFIELDS => $data
        );

        return $this->request($endpoint, array(), $options, 'POST');

    }


    /**
     * Order a transcript
     * @param string $filename The filename, including the path
     * @param string $name The name of the transcript
     * @param int|null $batch_id The ID of the batch/folder to place the transcript in
     * @param int $language_id The ID of the language used in the transcript
     */
    public function order($filename, $name, int $batch_id = null, int $language_id = 1) {
        
        $endpoint = '/transcripts/order/transcription';

        $source = $this->upload($filename, $name, $batch_id, $language_id);

        $data = array(
            'media_file_id' => $source['data']['id'],
        );

        $options = array(
            CURLOPT_POSTFIELDS => $data
        );

        return $this->request($endpoint, array(), $options, 'POST');

    }


    /**
     * Return the Transcript ID via Media File ID
     * @param int $file_id The File ID of the Transcript
     * @return string The transcript ID
     */
    public function id(int $file_id) {

        $endpoint = '/transcripts';

        $params = array(
            'media_file_id' => $file_id
        );

        $request = $this->request($endpoint, $params);

        return $request['data'][0]['id'];

    }


    /**
     * Return the Transcript Status
     * @param int $file_id The File ID of the Transcript
     * @return array
     */
    public function status(int $file_id) {

        $transcript_id = $this->id($file_id);

        $endpoint = '/transcripts/' . $transcript_id;

        return $this->request($endpoint);

    }


    /**
     * Get the transcript file info
     * @param in $id
     * @return array
     */
    public function fileInfo(int $id) {

        $endpoint = '/files/' . $id;

        return $this->request($endpoint);

    }


    /**
     * Get the Transcript Output Formats
     * @return array
     */
    public function outputFormats() {

        $endpoint = '/transcripts/output_formats';

        return $this->request($endpoint);

    }


    /**
     * Get the transcript file extension via output_format_id
     * @param int $format_id The Format ID
     * @return string The file extension
     */
    private function ext(int $format_id) {
        switch($format_id) {
            case 1:
                $ext = 'dfxp';
                break;
            case 2:
                $ext = 'cptxml';
                break;
            case 3:
                $ext = 'qt';
                break;
            case 4:
                $ext = 'rt';
                break;
            case 5:
                $ext = 'scc';
                break;
            case 6:
                $ext = 'stl';
                break;
            case 7:
                $ext = 'srt';
                break;
            case 8:
                $ext = 'adbe';
                break;
            case 9:
                $ext = 'smi';
                break;
            case 15:
                $ext = 'cloud';
                break;
            case 16:
                $ext = 'txt';
                break;
            case 17:
                $ext = 'doc';
                break;
            case 18:
                $ext = 'stampeddoc';
                break;
            case 19:
                $ext = 'tpm';
                break;
            case 20:
                $ext = 'html';
                break;
            case 28:
                $ext = 'js';
                break;
            case 29:
                $ext = 'json';
                break;
            case 33:
                $ext = 'sbv';
                break;
            case 46:
                $ext = 'pdf';
                break;
            case 48:
                $ext = 'wmp';
                break;
            case 51:
                $ext = 'vtt';
                break;
            case 53:
                $ext = 'avidds';
                break;
            case 70:
                $ext = 'iscc';
                break;
            case 71:
                $ext = 'smptett';
                break;
            case 72:
                $ext = 'pptxml';
                break;
            case 89:
                $ext = 'rtf';
                break;
            case 91:
                $ext = 'xml';
                break;
            case 93:
                $ext = 'tt';
                break;
            case 95:
                $ext = 'qtxml';
                break;
            case 98:
                $ext = 'xml';
                break;
            case 116:
                $ext = 'srt';
                break;
            case 126:
                $ext = 'itt';
                break;
            case 127:
                $ext = 'stampeddoc';
                break;
            case 136:
                $ext = 'html';
                break;
            case 138:
                $ext = 'vtt';
                break;
            case 139:
                $ext = 'vtt';
                break;
            case 158:
                $ext = 'docx';
                break;
            case 162:
                $ext = 'json';
                break;
            case 43:
                $ext = 'xml';
                break;
            default:
                $ext = 'txt';
        }

        return $ext;
    }


    /**
     * Generate Transcript file
     * @param int $file_id The File ID of the Transcript
     * @param int $format_id The output format ID
     */
    public function generateTranscript(int $file_id, int $format_id = 51) {

        $transcript_id = $this->id($file_id);

        $endpoint = '/transcripts/' . $transcript_id . '/text';

        $params = array(
            'output_format_id' => $format_id
        );

        $transcript = $this->request($endpoint, $params);

        $fileinfo = $this->fileInfo($file_id);

        $ext = $this->ext($format_id);

        $filename = '.cache/' . $fileinfo['data']['name'] . '.' . $ext;

        $file = fopen($filename, 'w') or die('Unable to create file.');
        fwrite($file, $transcript['data']);
        fclose($file);
        chmod($filename, 0777);

        return $filename;

    }


    /**
     * Download Transcript file
     * @param int $file_id The ID of the transcript
     * @param int $format_id The Output Format ID
     */
    public function download(int $file_id, int $format_id = 51) {

        $transcript_id = $this->id($file_id);

        $endpoint = '/transcripts/' . $transcript_id . '/text';

        $params = array(
            'output_format_id' => $format_id
        );

        $transcript = $this->request($endpoint, $params);

        $fileinfo = $this->fileInfo($file_id);

        $ext = $this->ext($format_id);
        $filename = $fileinfo['data']['name'] . '.' . $ext;

        header('Content-disposition: attachment; filename=' . $filename);
        header('Content-type: application/octet-stream');

        echo $transcript['data'];

    }


    /**
     * Get batch ID by Name and Create a new batch if it doesn't already exist
     * @param string $name The name of the Batch folder
     * @return int The ID of the batch
     */
    public function batchID(string $name) {

        $endpoint = '/batches';

        $params = array(
            //'name' => urlencode($name)
            'name' => $name
        );

        $batch = $this->request($endpoint, $params);

        if ( empty($batch['data']) ) {
            return $this->createBatch($name);
        } else {
            return $batch['data'][0]['id'];
        }

    }


    /**
     * Create a new batch and return its ID
     * @param string $name The name to give to the new Batch
     * @return int The Batch ID
     */
    public function createBatch(string $name) {
        $endpoint = '/batches';

        $params = array(
            //'name' => urlencode($name)
            'name' => $name
        );

        $batch = $this->request($endpoint, $params, array(), 'POST');

        return $batch['data']['id'];
    }


}

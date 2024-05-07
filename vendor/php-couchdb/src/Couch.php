<?php

namespace DB;

class Couch {

	const HTTP_METHODS_ALLOWED = ['GET','POST','PUT','DELETE','COPY'];

	private $dsn;
	private $db;
	private $options;

	public function getDsn() {
		return $this->dsn;
	}

	public function getParsedDsn() {
		return parse_url($this->getDsn());
	}

	public function getOptions() {
		return $this->options;
	}

	public function getDb() {
		return $this->db;
	}

	public function __construct ($dsn, $db, $options = []) {
		if (!function_exists('curl_init')) {
			throw new \Exception('cURL must be enabled');
		}
		$this->dsn = $dsn;
		$this->db = $db;
		$this->options = $options;
	}

	public function getDbs() {
		return $this->query('GET', '/_all_dbs');
	}

	public function createDb() {
		return $this->query('PUT', '/'.urlencode($this->db));
	}

	public function deleteDb() {
		return $this->query('DELETE', '/'.urlencode($this->db));
	}

	public function getDbInfos() {
		return $this->query('GET', '/'.urlencode($this->db));
	}

	public function saveDoc($doc) {
      $method = 'POST';
      $url = '/' . urlencode($this->db);
			if (is_array($doc)) {
				$doc = json_decode(json_encode($doc));
			}
      if (isset($doc->rev)) {
          $method = 'PUT';
          $url.= '/' . urlencode($doc->_id);
			}
      return $this->query($method, $url, [], $doc);
	}

	public function getDoc($id) {
		if (!strlen($id)) {
			throw new \InvalidArgumentException ("Document ID is empty");
		}
		$url = '/'.urlencode($this->db).'/'.urlencode($id);
		return $this->query('GET', $url);
	}

	public function storeAttachment($doc, $file, $contentType = 'application/octet-stream', $filename = null) {
		if (!is_object($doc)) {
			throw new InvalidArgumentException ("Document should be an object");
		}
		if(!$doc->_id) {
			throw new InvalidArgumentException ("Document should have an ID");
		}
		if (!is_file($file)) {
			throw new InvalidArgumentException ("File $file does not exist");
		}
		$url  = '/'.urlencode($this->db).'/'.urlencode($doc->_id).'/';
		$url .= empty($filename) ? basename($file) : $filename;
		if ($doc->_rev) {
			$url.='?rev='.$doc->_rev;
		}
		return $this->query('PUT', $url, [], $file);
	}

	public function deleteAttachment ($doc, $attachmentName) {
		if ( !is_object($doc)) {
			throw new InvalidArgumentException ("Document should be an object");
		}
		if (!$doc->_id) {
      throw new InvalidArgumentException ("Document should have an ID");
		}
		if (!$attachmentName) {
			throw new InvalidArgumentException ("Attachment name not set");
		}
		$url  = '/'.urlencode($this->db).'/'.urlencode($doc->_id).'/'.urlencode($attachmentName);
		return $this->query('DELETE', $url, ['rev' => $doc->_rev]);
	}

	public function query($method, $url, $parameters = [], $data = null) {
		$data = $this->basicQuery($method, $url, $parameters, $data);
		$response = self::parseResponse($data);
		if (!in_array($response['status_code'], [200,201,202]) ) {
			throw new \Exception($data);
		}
		return $response['body'];
	}

	private function basicQuery($method, $url, $parameters = [], $data = null) {
		if (!in_array($method, self::HTTP_METHODS_ALLOWED )) {
			throw new \Exception("HTTP method $method not allowed");
		}
		$url = $this->dsn.$url;
		if ($parameters) {
			$url .= http_build_query($parameters);
		}
		$http = curl_init($url);
		$options = [
				CURLOPT_HEADER => true,
        CURLOPT_RETURNTRANSFER => true,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_CUSTOMREQUEST => $method,
				CURLOPT_HTTPHEADER => ['Content-Type: application/json', 'Accept: application/json, text/html, text/plain, */*']
    ];
		if ($data) {
			if (!is_object($data) && is_file($data)) {
				$fstream = fopen($data,'r');
				$options[CURLOPT_INFILE] = $fstream;
				$options[CURLOPT_INFILESIZE] = filesize($data);
			} else {
				if ($method == 'COPY') {
					$options[CURLOPT_HTTPHEADER][] = json_encode($data);
				} else {
					$options[CURLOPT_POSTFIELDS] = json_encode($data);
				}
			}
		}
		curl_setopt_array($http, $options);
		$response = curl_exec($http);
		if (isset($fstream)) {
			fclose($fstream);
		}
		curl_close($http);
		return $response;
	}

	private static function parseResponse($data, $associative = false) {
		if (!strlen($data)) {
			throw new \InvalidArgumentException("no data to parse");
		}
		while (strpos($data, "HTTP/1.1 100 Continue\r\n") !== false) {
			$data = substr($data, strpos($data, "\r\n\r\n")+4);
    }
		$response = ['body' => null];
		list($headers, $body) = explode("\r\n\r\n", $data, 2);
		$tabHeaders = explode("\n", $headers);
		$status = reset($tabHeaders);
		$tabStatus = explode(' ', $status, 3);
		$response['status_code'] = trim($tabStatus[1]);
		$response['status_message'] = trim($tabStatus[2]);
		if (strlen($body)) {
			$response['body'] = (strpos($headers, 'Content-Type: application/json') !== false) ? json_decode($body, $associative) : $body;
		}
		return $response;
	}

}

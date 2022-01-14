<?php

class MailtargetApi {
	private $apiKey;
	private $companyId;
	private $apiUrl;

	public function __construct($apiKey, $companyId = false) {
		$apiKey = trim($apiKey);
		$companyId = trim($companyId);
		if (!$apiKey) {
			throw new Exception(__('Invalid API Key: '.$apiKey));
		}

		$this->apiKey = $apiKey;
		$this->companyId = $companyId;
		$this->apiUrl = 'https://apiv2.mtarget.co/v2';
	}

	public function ping () {
		return $this->get('/me');
	}

	public function getTeam () {
		return $this->get('/companies/detail');
	}

	public function getFormList ($page = 1) {
		return $this->get('/forms', [
            'order' => 'desc',
            'field' => 'lastUpdate',
            'page' => $page,
						'conditional' => false
        ]);
	}

	public function getCity ($country = 'Indonesia') {
		return $this->get('/city', [ 'country' => $country ]);
	}

	public function getCountry () {
		return $this->get('/country');
	}

	public function getFormDetail ($formId) {
		return $this->get('/forms/public/' . $formId);
	}

	public function submit ($input, $url) {
		return $this->post($url, $input);
		// return $this->postData($url . '/submit-data', $input);
	}

	private function get ($path, $params = array()) {
		$paramsString = '';
		if (!empty($params)) {
			foreach ($params as $key => $value) {
				$paramsString .= $key . '=' . $value . '&';
			}
		}
		// $paramsString .= 'accessToken=' . $this->apiKey;

		$url = $this->apiUrl . $path . '?' . $paramsString;

		$args = array(
			'timeout' => 5,
			'redirection' => 5,
			'httpversion' => '1.1',
			'user-agent'  => 'MailTarget Form Plugin/' . get_bloginfo( 'url' ),
			'headers'     => array(
					'Authorization' => 'Bearer ' . $this->apiKey,
			)
		);

		$request = wp_remote_get($url, $args);

		if (is_array($request) && $request['response']['code'] === 200) {
			return json_decode($request['body'], true);
		} elseif (is_array($request) && $request['response']['code']) {
		    $data = json_decode($request['body'], true);
			$error = new WP_Error('mailtarget-error', [
			    'method' => 'get',
			    'data' => $data,
                'code' => $request['response']['code']
            ]);
			return $error;
		} else {
			return false;
		}
	}

	private function post ($path, $data, $method = 'POST') {
		$args = array(
			'method' => $method,
			'timeout' => 5,
			'redirection' => 5,
			'httpversion' => '1.1',
			'user-agent'  => 'MailTarget Form Plugin/' . get_bloginfo( 'url' ),
			'headers'     => array(
				'Authorization' => 'Bearer ' . $this->apiKey,
			),
			'body' => json_encode($data)
		);

        $url = $this->apiUrl . $path;
		if (count(explode('://', $url)) > 1) $url = $path;

		$request = wp_remote_post($url, $args);

		if (is_array($request) && $request['response']['code'] === 200) {
			return json_decode($request['body'], true);
		} elseif (is_array($request) && $request['response']['code']) {
            $data = json_decode($request['body'], true);
		    if ($data['code'] === 416) {
                return json_decode($request['body'], true);
            } else {
                $error = new WP_Error('mailtarget-error', [
                    'method' => 'post',
                    'data' => $data,
                    'code' => $request['response']['code']
                ]);
                return $error;
            }
		} else {
			return false;
		}
	}

	private function postData ($path, $data, $method = 'POST') {
		$boundary = wp_generate_password( 24 );
		$payload = '';
		foreach ($data as $name => $value) {
			if (substr($value, 0, 15) == 'mtFormFilename:' && strlen($value) <= 33) {
				$payload .= '--' . $boundary;
				$payload .= "\r\n";
				$payload .= 'Content-Disposition: form-data; name="' . $name . '"' . "\r\n\r\n";
				$payload .= '';
				$payload .= "\r\n";
			} else if (substr($value, 0, 15) == 'mtFormFilename:' && strlen($value) > 33) {
				$file = explode('###', $value);
				$filename = substr($file[0], 15);
				$local_file = substr($file[1], 15);
				$payload .= '--' . $boundary;
				$payload .= "\r\n";
				$payload .= 'Content-Disposition: form-data; name="' . $name . '"; filename="' . $filename . '"' . "\r\n";
				// $payload .= 'Content-Type: image/jpeg' . "\r\n";
				$payload .= "\r\n";
				$payload .= file_get_contents( $local_file );
				$payload .= "\r\n";
			} else {
				$payload .= '--' . $boundary;
				$payload .= "\r\n";
				$payload .= 'Content-Disposition: form-data; name="' . $name . '"' . "\r\n\r\n";
				$payload .= $value;
				$payload .= "\r\n";
			}
		}
		$payload .= '--' . $boundary . '--';

		$headers  = array(
			'Authorization' => 'Bearer ' . $this->apiKey,
			'accept' => 'application/json', // The API returns JSON
			'content-type' => 'multipart/form-data;boundary=' . $boundary, // Set content type to multipart/form-data
		);

		$args = array(
			'method' => $method,
			'timeout' => 5,
			'redirection' => 5,
			'httpversion' => '1.1',
			'user-agent' => 'MailTarget Form Plugin/' . get_bloginfo( 'url' ),
			'headers' => $headers,
			'body' => $payload
		);

		$url = $this->apiUrl . $path;
		if (count(explode('://', $url)) > 1) $url = $path;

		var_dump(array( 'headers' => $headers ));
		var_dump(array( 'payload' => $payload ));
		var_dump(array( 'args' => $args ));
	
		$request = wp_remote_post($url, $args);

		var_dump(array( 'request' => $request ));

		if (is_array($request) && $request['response']['code'] === 200) {
			return json_decode($request['body'], true);
		} elseif (is_array($request) && $request['response']['code']) {
            $data = json_decode($request['body'], true);
		    if ($data['code'] === 416) {
                return json_decode($request['body'], true);
            } else {
                $error = new WP_Error('mailtarget-error', [
                    'method' => 'post',
                    'data' => $data,
                    'code' => $request['response']['code']
                ]);
                return $error;
            }
		} else {
			return false;
		}
	}
}
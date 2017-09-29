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
		$this->apiUrl = 'https://api.mailtarget.co';
	}

	public function ping () {
		return $this->get('/user/ping', ['accessToken' => $this->apiKey]);
	}

	public function getTeam () {
		return $this->get('/company/default', [ 'accessToken' => $this->apiKey ]);
	}

	public function getFormList () {
		return $this->get('/form', [ 'accessToken' => $this->apiKey, 'companyId' => $this->companyId ]);
	}

	public function getFormDetail ($formId) {
		return $this->get('/form/' . $formId, [ 'accessToken' => $this->apiKey, 'companyId' => $this->companyId ]);
	}

	private function get ($path, $params = array()) {
		$paramsString = '';
		if (!empty($params)) {
			foreach ($params as $key => $value) {
				$paramsString .= $key . '=' . $value . '&';
			}
		}
		$paramsString .= 'accessToken=' . $this->apiKey;

		$url = $this->apiUrl . $path . '?' . $paramsString;

		$args = array(
			'timeout' => 5,
			'redirection' => 5,
			'httpversion' => '1.1',
			'user-agent'  => 'MailTarget Form Plugin/' . get_bloginfo( 'url' )
		);

		$request = wp_remote_get($url, $args);

		if (is_array($request) && $request['response']['code'] === 200) {
			return json_decode($request['body'], true);
		} elseif (is_array($request) && $request['response']['code']) {
			$error = json_decode($request['body'], true);
			$error = new WP_Error('mailtarget-error-get', $error);
			return $error;
		} else {
			return false;
		}
	}

	private function post ($path, $data, $method = 'POST') {
		$data['accessToken'] = $this->apiKey;

		$args = array(
			'method' => $method,
			'timeout' => 5,
			'redirection' => 5,
			'httpversion' => '1.1',
			'user-agent'  => 'MailTarget Form Plugin/' . get_bloginfo( 'url' ),
			'body' => json_encode($data)
		);

		$url = $this->apiUrl . $path;

		$request = wp_remote_post($url, $args);

		if (is_array($request) && $request['response']['code'] === 200) {
			return json_decode($request['body'], true);
		} elseif (is_array($request) && $request['response']['code']) {
			$error = json_decode($request['body'], true);
			$error = new WP_Error('mailtarget-error-get', $error['detail']);
			return $error;
		} else {
			return false;
		}
	}
}
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

	public function getFormList ($page = 1) {
		return $this->get('/form', [
		    'accessToken' => $this->apiKey,
            'companyId' => $this->companyId,
            'order' => 'desc',
            'field' => 'lastUpdate',
            'page' => $page
        ]);
	}

	public function getCity ($country = 'indonesia') {
		return $this->get('/city', [ 'accessToken' => $country ]);
	}

	public function getCountry () {
		return $this->get('/country');
	}

	public function getFormDetail ($formId) {
		return $this->get('/form/public/' . $formId);
	}

	public function submit ($input, $url) {
		return $this->post($url, $input);
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
}
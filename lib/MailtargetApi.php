<?php //phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Mailtarget Form
 *
 * Mailtarget Form.
 *
 * @category   Mailtarget Form
 * @package    Mailtarget Form
 * @subpackage Mailtarget Form
 */
class MailtargetApi {

	/**
	 * Var api key
	 *
	 * @var string
	 */
	private $api_key;

	/**
	 * Var company id
	 *
	 * @var string
	 */
	private $company_id;

	/**
	 * Var api Url
	 *
	 * @var boolean
	 */
	private $api_url;

	/**
	 * Class __construct.
	 *
	 * @param string  $api_key api key.
	 * @param boolean $company_id company id.
	 * @throws Exception If the api_key is not found.
	 */
	public function __construct( $api_key, $company_id = false ) {
		$api_key    = trim( $api_key );
		$company_id = trim( $company_id );
		if ( ! $api_key ) {
			throw new Exception( 'Invalid API Key: ' . $api_key );
		}

		$this->api_key    = $api_key;
		$this->company_id = $company_id;
		$this->api_url    = 'https://apiv2.mtarget.co/v2';
	}

	/**
	 * Function ping.
	 */
	public function ping() {
		return $this->get( '/me' );
	}

	/**
	 * Function get company detail.
	 */
	public function get_team() {
		return $this->get( '/companies/detail' );
	}


	/**
	 * Function get list of form.
	 *
	 * @param number $page page.
	 */
	public function get_form_list( $page = 1 ) {
		return $this->get(
			'/forms',
			array(
				'order'       => 'desc',
				'field'       => 'lastUpdate',
				'page'        => $page,
				'conditional' => false,
			)
		);
	}

	/**
	 * Function get list of city from a country.
	 *
	 * @param string $country country.
	 */
	public function get_city( $country = 'Indonesia' ) {
		return $this->get( '/city', array( 'country' => $country ) );
	}

	/**
	 * Function get list of country.
	 */
	public function get_country() {
		return $this->get( '/country' );
	}

	/**
	 * Function get form detail  by id.
	 *
	 * @param string $form_id form_id.
	 */
	public function get_form_detail( $form_id ) {
		return $this->get( '/forms/public/' . $form_id );
	}

	/**
	 * Function post submit form.
	 *
	 * @param object $input input.
	 * @param string $url url.
	 */
	public function submit( $input, $url ) {
		return $this->post( $url, $input );
	}

	/**
	 * Function get.
	 *
	 * @param string $path path.
	 * @param array  $params params.
	 */
	private function get( $path, $params = array() ) {
		$params_string = '';
		if ( ! empty( $params ) ) {
			foreach ( $params as $key => $value ) {
				$params_string .= $key . '=' . $value . '&';
			}
		}

		$url = $this->api_url . $path . '?' . $params_string;

		$args = array(
			'timeout'     => 5,
			'redirection' => 5,
			'httpversion' => '1.1',
			'user-agent'  => 'MailTarget Form Plugin/' . get_bloginfo( 'url' ),
			'headers'     => array(
				'Authorization' => 'Bearer ' . $this->api_key,
			),
		);

		$request = wp_remote_get( $url, $args );

		if ( is_array( $request ) && 200 === $request['response']['code'] ) {
			return json_decode( $request['body'], true );
		} elseif ( is_array( $request ) && $request['response']['code'] ) {
			$data  = json_decode( $request['body'], true );
			$error = new WP_Error(
				'mailtarget-error',
				array(
					'method' => 'get',
					'data'   => $data,
					'code'   => $request['response']['code'],
				)
			);
			return $error;
		} else {
			return false;
		}
	}

	/**
	 * Function post.
	 *
	 * @param string $path path.
	 * @param object $data data.
	 * @param string $method method.
	 */
	private function post( $path, $data, $method = 'POST' ) {
		$args = array(
			'method'      => $method,
			'timeout'     => 5,
			'redirection' => 5,
			'httpversion' => '1.1',
			'user-agent'  => 'MailTarget Form Plugin/' . get_bloginfo( 'url' ),
			'headers'     => array(
				'Authorization' => 'Bearer ' . $this->api_key,
			),
			'body'        => wp_json_encode( $data ),
		);

		$url = $this->api_url . $path;
		if ( count( explode( '://', $url ) ) > 1 ) {
			$url = $path;
		}

		$request = wp_remote_post( $url, $args );

		if ( is_array( $request ) && 200 === $request['response']['code'] ) {
			return json_decode( $request['body'], true );
		} elseif ( is_array( $request ) && $request['response']['code'] ) {
			$data = json_decode( $request['body'], true );
			if ( 416 === $data['code'] ) {
				return json_decode( $request['body'], true );
			} else {
				$error = new WP_Error(
					'mailtarget-error',
					array(
						'method' => 'post',
						'data'   => $data,
						'code'   => $request['response']['code'],
					)
				);
				return $error;
			}
		} else {
			return false;
		}
	}

	/**
	 * Function post Data.
	 *
	 * @param string $path path.
	 * @param object $data data.
	 * @param string $method method.
	 */
	private function post_data( $path, $data, $method = 'POST' ) {
		$boundary = wp_generate_password( 24 );
		$payload  = '';
		foreach ( $data as $name => $value ) {
			if ( substr( $value, 0, 15 ) === 'mtFormFilename:' && strlen( $value ) <= 33 ) {
				$payload .= '--' . $boundary;
				$payload .= "\r\n";
				$payload .= 'Content-Disposition: form-data; name="' . $name . '"' . "\r\n\r\n";
				$payload .= '';
				$payload .= "\r\n";
			} elseif ( substr( $value, 0, 15 ) === 'mtFormFilename:' && strlen( $value ) > 33 ) {
				$file       = explode( '###', $value );
				$filename   = substr( $file[0], 15 );
				$local_file = substr( $file[1], 15 );
				$payload   .= '--' . $boundary;
				$payload   .= "\r\n";
				$payload   .= 'Content-Disposition: form-data; name="' . $name . '"; filename="' . $filename . '"' . "\r\n";
				// $payload .= 'Content-Type: image/jpeg' . "\r\n"; prev code.
				$payload .= "\r\n";
				// $payload .= file_get_contents( $local_file ); prev code.
				$payload .= wp_remote_get( $local_file );
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

		$headers = array(
			'Authorization' => 'Bearer ' . $this->api_key,
			'accept'        => 'application/json', // The API returns JSON.
			'content-type'  => 'multipart/form-data;boundary=' . $boundary, // Set content type to multipart/form-data.
		);

		$args = array(
			'method'      => $method,
			'timeout'     => 5,
			'redirection' => 5,
			'httpversion' => '1.1',
			'user-agent'  => 'MailTarget Form Plugin/' . get_bloginfo( 'url' ),
			'headers'     => $headers,
			'body'        => $payload,
		);

		$url = $this->api_url . $path;
		if ( count( explode( '://', $url ) ) > 1 ) {
			$url = $path;
		}

		$request = wp_remote_post( $url, $args );

		if ( is_array( $request ) && 200 === $request['response']['code'] ) {
			return json_decode( $request['body'], true );
		} elseif ( is_array( $request ) && $request['response']['code'] ) {
			$data = json_decode( $request['body'], true );
			if ( 416 === $data['code'] ) {
				return json_decode( $request['body'], true );
			} else {
				$error = new WP_Error(
					'mailtarget-error',
					array(
						'method' => 'post',
						'data'   => $data,
						'code'   => $request['response']['code'],
					)
				);
				return $error;
			}
		} else {
			return false;
		}
	}
}

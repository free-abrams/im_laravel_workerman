<?php


namespace App\Services;

/**
 * token 类
 * Created By FreeAbrams
 * Date: 2022/1/8
 */
class TokenService
{
	
	public function __construct()
	{
		$this->publicKey = config('api.publicKey');
		$this->privateKey = config('api.privateKey');
	}
	
	/**
	 * 加密
	 * @param array $data
	 * @return bool|string
	 */
	public function encrypt(array $data = [])
	{
		$this->publicKey = "-----BEGIN PUBLIC KEY-----\n" .
            wordwrap($this->publicKey, 64, "\n", true) .
            "\n-----END PUBLIC KEY-----";
		$result = '';
		$bool = openssl_public_encrypt(json_encode($data), $result, $this->publicKey);
		return $bool?base64_encode($result):false;
	}
	
	/**
	 * 解密
	 * @param string $string
	 * @return bool|string
	 */
	public function decrypt(string $string)
	{
		$this->privateKey = "-----BEGIN RSA PRIVATE KEY-----\n" .
            wordwrap($this->privateKey, 64, "\n", true) .
            "\n-----END RSA PRIVATE KEY-----";
		$result = '';
		$bool = openssl_private_decrypt(base64_decode($string), $result, $this->privateKey);
		return $result?$result:false;
	}
	
	
}
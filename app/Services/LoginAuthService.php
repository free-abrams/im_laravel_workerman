<?php


namespace App\Services;

/**
 * Created By FreeAbrams
 * Date: 2022/1/8
 */
class LoginAuthService
{
	/**
	 * @var string 用户唯一凭证
	 */
	private $uid = 'A00000';
	
	/**
	 * @var array 加密数据
	 */
	private $data = [];
	/**
	 * LoginAuthService constructor.
	 * @param string $uniqueUid 用户唯一凭证
	 * @param array $data 加密数据
	 */
	public function __construct(string $uniqueUid, array $data = [])
	{
		$this->uid = $uniqueUid;
		$this->data = $data;
	}
	
	public function encryptionToken()
	{
		$token = new TokenService();
		
		return $token->encrypt(array_merge([$this->uid], $this->data));
	}
	
	public function verifyToken($string)
	{
		$token = new TokenService();
		
		return $token->decrypt($string);
	}
	
}
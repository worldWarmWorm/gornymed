<?php
/**
 * DWebUser
 */
class DWebUser extends \CWebUser
{
	public $role;
	
	public function login(IUserIdentity $identity, $duration=0)
	{
		$this->role = $identity->role;
		return parent::login($identity, $duration);
	}
}
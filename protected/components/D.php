<?php
/**
 * Набор полезных функций
 */
class D
{
	/**
	 * Получить компонент d
	 * @return DApi \Yii::app()->d
	 */
	public static function yd()
	{
		return \Yii::app()->d;
	}
	
	/**
	 * Результат условия.
	 * @param boolean $if результат условия.
	 * @param string $then значение на вывод при результате условия TRUE.
	 * @param string $else значение на вывод при результате условия FALSE. 
	 * По умолчанию пустая строка.
	 */
	public static function c($if, $then, $else=null)
	{
		return $if ? $then : $else;
	}
	
	/**
	 * Проверяет роль пользователя
	 * @param string $role роль пользователя.
	 */
	public static function role($role)
	{
		// return (\Yii::app()->user->role === $role);
		if($role == 'admin' && Yii::app()->user->getState('role') == 'sadmin') return true;
		
		return (Yii::app()->user->getState('role') === $role);
	}
	
	/**
	 * Получить значение переменной из настроек CMS
	 * @param string $param имя параметра.
	 */
	public static function cms($param, $default=null)
	{
		return \Yii::app()->settings->get('cms_settings', $param, $default);	
	} 
	
	/**
	 * Получить значение переменной из настроек CMS
	 * @param string $param имя параметра.
	 */
	public static function shop($param, $default=null)
	{
		return \Yii::app()->settings->get('shop_settings', $param) ?: $default;
	}
}
<?php
namespace samson\core;

/**
 * Идентификатор указывающий на использование базового контроллера модуля
 */
define( 'MOD_BASE_CONTROLLER', 234 );
/**
 * Идентификатор указывающий на использование универсального контроллера модуля
 */
define( 'MOD_UNI_CONTROLLER', 235 );
/**
 * Идентификатор указывающий на использование контроллера GET модуля
 */
define( 'MOD_GET_CONTROLLER', 236 );
/**
 * Идентификатор указывающий на использование контроллера PUT модуля
 */
define( 'MOD_PUT_CONTROLLER', 237 );
/**
 * Идентификатор указывающий на использование контроллера POST модуля
 */
define( 'MOD_POST_CONTROLLER', 238 );
/**
 * Идентификатор указывающий на использование контроллера DELETE модуля
 */
define( 'MOD_DELETE_CONTROLLER', 239 );

/**
 * Интерфейс для модуля системы
 * @author Vitaly Iegorov <vitalyiegorov@gmail.com>
 *
 */
interface iModule
{
	/** Identifier for default view data entry */
	const VD_POINTER_DEF = '';
	
	/** Идентификатор указывающий на использование базового контроллера модуля */
	const BASE_CONTROLLER = MOD_BASE_CONTROLLER;
	
	/** Идентификатор указывающий на использование универсального контроллера модуля */
	const UNI_CONTROLLER = MOD_UNI_CONTROLLER;
	
	/** Идентификатор указывающий на использование контроллера GET модуля */
	const GET_CONTROLLER = MOD_GET_CONTROLLER;
	
	/** Идентификатор указывающий на использование контроллера POST модуля */
	const POST_CONTROLLER = MOD_POST_CONTROLLER;
	
	/** Идентификатор указывающий на использование контроллера PUT модуля */
	const PUT_CONTROLLER = MOD_PUT_CONTROLLER;
	
	/** Идентификатор указывающий на использование контроллера DELETE модуля */
	const DELETE_CONTROLLER = MOD_DELETE_CONTROLLER;
	
	/**
	 * Установить заголовок страницы
	 * @param string $title Значение для заголовка страницы
	 */
	public function title( $title = NULL );
	
	/**
	 * Получить реальный идентификатор модуля
	 * @return string Реальный идентификатор модуля
	 */
	public function id();
	
	/**
	 * Получить идентификатор модуля под которым он был загружен в систему
	 * @return string Идентификатор модуля под которым он был загружен в систему
	 */
	public function core_id();
	
	/**
	 * Установить/Получить относительный путь к файлам данного модуля
	 *
	 * @param string $value Значение пути к файлам модуля для установки
	 * @return iModule Указатель на самого себя для "ЦЕПИРОВАНИЯ"
	 */
	public function path( $value = NULL );	
	
	/**
	 * Вывести представление данного модуля в текущий поток вывода
	 *
	 * @param string $view_path Путь к представлению относительно расположения данного модуля
	 */
	public function output( $view_path );
	
	/**
	 * Выполнить прорисовку данного модуля
	 * Если передан метод контроллера модуля, попытаемся его выпонить перед прорисовкой модуля 
	 * 
	 * @param string $controller Метод контроллера для выполнения
	 */
	public function render( $controller = NULL);
	
	/**
	 * Выполнить метод контроллера, варианты выполнения:
	 * 	- Если ничего не передано то выполним базовый контроллер модуля
	 * 		<code>ИМЯ_МОДУЛЯ( $p1, $p2, ... )</code> 
	 * 	- Если базовый контроллер не найден то выполняется попытка вызова универсального контроллера
	 * 		<code>ИМЯ_МОДУЛЯ__HANDLER( $p1, $p2, ... )</code> 
	 * 	- Если передан специальный идентификатор типа контроллера то выполняется попытка его вызова 
	 * 		<code>ИМЯ_МОДУЛЯ__[POST,PUT,DELETE]( $p1, $p2, ... )</code>
	 *
	 * @param string $method_name 	Имя метода контроллера для выполнения
	 * @return integer Результат выполнения метода контроллера
	 */
	public function action( $method_name = NULL );
	
	/**
	 * Set current view path and manipulate view data collection pointer 
	 * 
	 * Module saves all view data that has been set to a specific view in appropriate
	 * view data collection entry. By default module creates vied data entry - VD_POINTER_DEF,
	 * and until any call of iModule::view() or iModule::output(), all data that is iModule::set(),
	 * is stored to that location. 
	 * 
	 * On the first call of iModule::view() or iModule::output(), this method changes the view data
	 * pointer to actual relative view path, and copies(actually just sets view data pointer) all view
	 * data setted before to new view data pointer. This guarantees backward compatibility and gives
	 * opportunity not to set the view path before settiing view data to it.  
	 * 	 
	 * 
	 * @param string $value	Путь к файлу представлению
	 * @return iModule Указатель на текущий модуль для цепирования
	 */
	public function view( $view_path );
	
	/**
	 * Установить/Получить HTML представление модуля
	 * 
	 * @param string $value Значение HTML для установки в представление
	 * @return iModule/string Указатель на текущий модуль для цепирования или текущее значение HTML представления 
	 */
	public function html( $value = NULL );
	
	/**
	 * Установить значение переменной представления модуля
	 * 
	 * Метод также может принимать:
	 *  - Объекты наследованные от dbRecord
	 *  	В представление устанавливаются все поля записи из БД в формате:  <code>ClassName_FieldName</code>
	 *  - Модули
	 *  	В представление устанавливаются все переменные представления переданного модуля
	 *  - Ассоциативные массивы
	 *  	В представление устанавливаются все из переданного массива
	 *  	
	 * При этом все переменные установленные до этого сохраняются
	 * При передаче сложного объекта параметр <code>$value</code> упускается 
	 *  
	 * @param mixed $field Имя перенной для установки или объект
	 * @param mixed $value Значение переменной 
	 */
	public function set( $field, $value = NULL );
	
	/**
	 * Создать дупликат для текущего модуля
	 * 
	 * @param string $id 			Идентификатор для дупликата модуля
	 * @param string $class_name 	Имя класса для создаваемого модуля
	 * @deprecated Use clone instead
	 * @return ModuleConnector Указаетель на дупликат текущего модуля	 
	 */
	public function & duplicate( $id, $class_name = null );
}
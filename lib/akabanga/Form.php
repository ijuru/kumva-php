<?php
/**
 * This file is part of Akabanga.
 *
 * Akabanga is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Akabanga is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Akabanga.  If not, see <http://www.gnu.org/licenses/>.
 *
 * Copyright Rowan Seymour 2010
 * 
 * Purpose: Form class
 */
 
/**
 * Base class for forms with property binding
 */
abstract class Form {
	private $validator;
	private $successUrl;
	private $entity;
	private $errors;
	private $properties;
	private $renderer;
	private $autoTrim = TRUE;
	
	private static $controlPrefix = '_ctrl_';

	/**
	 * Contructs a new form
	 * @param string successUrl the URL to reditrect to on save success or cancel
	 * @param Validator validator the entity validator
	 */
	public function __construct($successUrl, $validator = NULL, $renderer = NULL) {
		$this->successUrl = $successUrl;
		$this->validator = $validator;
		$this->errors = new Errors();	
		$this->entity = $this->createEntity();
		$this->renderer = $renderer ? $renderer : new Renderer();
		
		$action = Request::getPostParam('_action', '');
		if ($action == 'bind' || $action == 'save')
			$this->bind();
		if ($action == 'save')
			$this->save();
	}
	
	/**
	 * Binds object values to incoming POST parameters
	 */
	private function bind() {
		// Get list of properties to bind
		$properties = aka_parsecsv(Request::getPostParam('_properties', ''));
	
		foreach ($properties as $property) {
			// Get post param (defaults to FALSE because unchecked checkboxes don't post anything)
			$value = Request::getPostParam($this->getControlName($property), FALSE);
			if ($value && $this->autoTrim)
				$value = trim($value);
			BeanUtils::setProperty($this->entity, $property, $value);
		} 
		
		// Give subclasses a chance to do extra binding
		$this->onBind($this->entity);
	}
	
	/**
	 * Saves the form by binding, validating and saving the entity
	 */
	private function save() {
		// Do validation if validator was provided
		if ($this->validator != NULL) {
			$this->validator->validate($this->entity, $this->errors);
			if (!$this->errors->isEmpty()) {
				$this->errors->add(KU_MSG_VALIDATIONERRORS);
				return;
			}
		}
		
		// Saving is wrapped in a database transaction
		Database::getCurrent()->beginTransaction();
		
		// Call custom save function, if successful then redirect to success URL
		if ($this->saveEntity($this->entity)) {
			Database::getCurrent()->commitTransaction();
			Request::redirect($this->successUrl);
		}
		else {
			$this->errors->add(KU_MSG_UNABLETOSAVE);
			Database::getCurrent()->rollbackTransaction();
		}
	}
	
	/**
	 * Creates or loads the entity to be edited by this form
	 * @return Entity the entity
	 */
	protected abstract function createEntity();
	
	/**
	 * Saves the entity after successful binding and validation
	 * @param Entity the entity to save
	 * @return bool TRUE if save was successful, else FALSE
	 */
	protected abstract function saveEntity($entity);
	
	/**
	 * Gets the entity being edited by this form
	 * @return Entity the entity being edited
	 */
	public function getEntity() {
		return $this->entity;	
	}
	
	/**
	 * Gets the whether the entity is new
	 * @return bool TRUE if form entity is new
	 */
	public function isNewEntity() {
		return $this->entity->isNew();	
	}
	
	/**
	 * Gets the validation errors object
	 * @return Errors the validation errors
	 */
	public function getErrors() {
		return $this->errors;
	}
	
	/**
	 * Subclasses can perform extra binding by overriding this method
	 * @param Entity the form's entity
	 */
	protected function onBind($entity) {
	}
	
	/**
	 * Generates a HTML control id/name from a property name
	 * @param string property the property name
	 * @return string the control name
	 */
	protected function getControlName($property) {
		return self::$controlPrefix.$property;
	}
	
	/**
	 * Registers the given property name so that it will be included during binding
	 * @param string property the name of the property
	 */
	protected function registerProperty($property) {
		$this->properties[] = $property;
	}
	
	/**
	 * Gets the success URL
	 * @return string the success URL
	 */
	public function getSuccessUrl() {
		return $this->successUrl;
	}
	
	/**
	 * Sets the success URL
	 * @param string successUrl the success URL
	 */
	protected function setSuccessUrl($successUrl) {
		$this->successUrl = $successUrl;
	}
	
	/**
	 * Sets whether form values should be automatically trimmed during binding
	 * @param bool autoTrim TRUE if values should be trimmed
	 */
	public function setAutoTrim($autoTrim) {
		$this->autoTrim = $autoTrim;
	}
	
	/**
	 * Creates the opening form tag and some required hidden fields
	 * @param string id the id of the form
	 * @param string onSubmit the javascript to be excuted when form is submitted (optional)
	 */
	public function start($id) {
		echo '<form id="'.$id.'" method="post" action="">';
		$this->renderer->hidden('_action', 'bind');
		$this->hidden('id');
	}
	
	/**
	 * For completeness sake, a method to close the form tag
	 */
	public function end() {
		$this->renderer->hidden('_properties', aka_makecsv($this->properties));
		echo '</form>';
	}
	
	/**
	 * Creates a bound text-field
	 * @param string property the name of the property to be bound
	 * @param string class the CSS class
	 */
	public function textField($property, $class = 'text') {
		$name = $this->getControlName($property);
		$value = BeanUtils::getProperty($this->entity, $property);
		$this->renderer->textField($name, $value, $class);
		$this->registerProperty($property);
	}
	
	/**
	 * Creates a bound text-area
	 * @param string property the name of the property to be bound
	 * @param string class the CSS class
	 */
	public function textArea($property, $class = 'text') {
		$name = $this->getControlName($property);
		$value = BeanUtils::getProperty($this->entity, $property);
		$this->renderer->textArea($name, $value, $class);
		$this->registerProperty($property);
	}
	
	/**
	 * Creates a bound checkbox
	 * @param string property the name of the property to be bound
	 * @param string class the CSS class
	 */
	public function checkbox($property, $class = 'checkbox') {
		$name = $this->getControlName($property);
		$value = (bool)BeanUtils::getProperty($this->entity, $property, TRUE);
		$this->renderer->checkbox($name, $value, $class);
		$this->registerProperty($property);
	}
	
	/**
	 * Creates a dropdown list
	 * @param string property the name of the property to be bound
	 * @param array options the options
	 * @param bool keyAsValue TRUE if keys from options array should be come option values
	 * @param string class the CSS class 
	 */
	public function dropdown($property, $options, $keyAsValue = TRUE, $class = 'dropdown') {
		$name = $this->getControlName($property);
		$value = BeanUtils::getProperty($this->entity, $property);
		$this->renderer->dropdown($name, $value, $options, $keyAsValue, $class);
		$this->registerProperty($property);
	}
	
	/**
	 * Creates a bound hidden field
	 * @param string property the name of the property to be bound
	 */
	public function hidden($property) {
		$name = $this->getControlName($property);
		$value = BeanUtils::getProperty($this->entity, $property);
		$this->renderer->hidden($name, $value);
		$this->registerProperty($property);
	}
	
	/**
	 * Displays the errors for the given property
	 * @param string property the property (NULL for object errors)
	 * @param string class the CSS class
	 */
	public function errors($property = NULL, $class = 'error') {
		$messages = $property ? $this->errors->getForProperty($property) : $this->errors->get();
		$this->renderer->errors($messages, $class);
	}
	
	/**
	 * Creates a save button
	 * @param string class the CSS class
	 */
	public function saveButton($class = 'button') {
		$this->renderer->saveButton($class);
	}
	
	/**
	 * Creates a cancel button
	 * @param string class the CSS class
	 */
	public function cancelButton($class = 'button') {
		$this->renderer->cancelButton($this->successUrl, $class);
	}
}

?>

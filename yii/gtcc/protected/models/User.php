<?php

/**
 * This is the MongoDB Document model class based on table "tbl_user".
 */
class User extends EMongoDocument
{
	public $username;
	public $pwd;
	public $email;

	/**
	 * Returns the static model of the specified AR class.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * returns the primary key field for this model
	 */
	public function primaryKey()
	{
		return '_id';
	}

	/**
	 * @return string the associated collection name
	 */
	public function getCollectionName()
	{
		return 'User';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username, pwd, email', 'required'),
			array('username, pwd, email', 'length', 'max'=>128),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('_id, username, pwd, email', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'username' => 'Username',
			'pwd' => 'Password',
			'email' => 'Email',
		);
	}
}
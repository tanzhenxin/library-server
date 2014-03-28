<?php

/**
 * This is the MongoDB Document model class based on table "tbl_book".
 */
class MyLibrary extends EMongoDocument
{        
	/**
	 * Returns the static model of the specified AR class.
	 * @return Book the static model class
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
		return 'BorrowHistory';
	}
}
<?php

/**
 * This is the MongoDB Document model class based on table "tbl_book".
 */
class MyLibrary extends EMongoDocument
{
        public $bookTag;
	public $bookName;
        public $borrowDate;
        public $planReturnDate;

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

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('bookTag,bookName, borrowDate, planReturnDate', 'required'),
                        array('bookTag', 'length', 'max'=>30),	
                        array('bookName', 'length', 'max'=>48),
			array('borrowDate', 'length', 'max'=>10),
			array('planReturnDate', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('_id, bookName, borrowDate, planReturnDate', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'_id' => 'ID',
                        'bookTag' => 'Book Tag',
			'bookName' => 'Book Name',
			'startBorrowDate' => 'Borrowed Date',
			'planReturnDate' => 'Plan to Return Date',
		);
	}
}
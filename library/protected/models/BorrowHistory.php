<?php

/**
 * This is the MongoDB Document model class based on table "tbl_borrowhistory".
 */
class BorrowHistory extends EMongoDocument
{	
	public $planReturnDate;
        public $realReturnDate;
        public $startBorrowDate;
        public $userId;
        public $userName;
        public $bookId;
        public $bookTag;
        public $bookName;
        public $ISBN;

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
			array('planReturnDate, realReturnDate, startBorrowDate, userId, userName, bookId, bookName, bookTag,ISBN', 'required'),
			array('planReturnDate', 'length', 'max'=>10),
                        array('planReturnDate', 'length', 'max'=>10),
                        array('realReturnDate', 'length', 'max'=>10),
                        array('startBorrowDate', 'length', 'max'=>10),
                        array('userId', 'length', 'max'=>64),
                        array('userName', 'length', 'max'=>50),
                        array('bookId', 'length', 'max'=>64),
                        array('userId', 'length', 'max'=>128),
                        array('bookName', 'length', 'max'=>128),
                        array('bookTag', 'length', 'max'=>30),
                        array('ISBN', 'length', 'max'=>30),
                 
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('_id, planReturnDate, realReturnDate, startBorrowDate, userId, userName, bookId, bookName, bookTag, ISBN', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
                    'planReturnDate' => 'Due Date',
                    'realReturnDate' => 'Actual Return Date',
                    'startBorrowDate' => 'Start Borrow Date',
                    'userId' => 'User Id',
                    'userName' => 'User Name',
                    'bookId' => 'bookId',
                    'bookName' => 'Book Name',
                    'bookTag' => 'Book Tag',
                    'ISBN' => 'ISBN',
                    '_id' => 'ID',
			
		);
	}
}
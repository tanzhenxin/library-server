<?php

/**
 * This is the MongoDB Document model class based on table "tbl_book".
 */
class Book extends EMongoDocument
{
	
	public $ISBN;
	public $title;
	public $price;
	public $BianHao;
	public $description;
	public $author;
	public $publishedDate;
	public $publisher;
	public $language;
	public $printLength;

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
		return 'Book';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ISBN, title, price, BianHao, description, author, publishedDate, publisher, language, printLength', 'required'),
			array('printLength', 'numerical', 'integerOnly'=>true),
			array('ISBN', 'length', 'max'=>48),
			array('title, price, author', 'length', 'max'=>128),
			array('BianHao', 'length', 'max'=>10),
			array('description', 'length', 'max'=>2048),
			array('publishedDate', 'length', 'max'=>24),
			array('publisher', 'length', 'max'=>50),
			array('language', 'length', 'max'=>20),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('_id, ISBN, title, price, BianHao, description, author, publishedDate, publisher, language, printLength', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'_id' => 'ID',
			'ISBN' => 'ISBN',
			'title' => 'Title',
			'price' => 'Price',
			'BianHao' => 'GTCC Book Tag',
			'description' => 'Description',
			'author' => 'Author',
			'publishedDate' => 'Published Date',
			'publisher' => 'Publisher',
			'language' => 'Language',
			'printLength' => 'Print Length',
		);
	}
}
<?php
use Json\Commands\BorrowResponse;

use Doctrine\ODM\MongoDB\Mapping\Annotations\String;

use Json\Data\CBorrowHistory;

use Json\Commands\BaseResponse;
use Constant\ErrorCode;
use Models\Book;
include_once __DIR__ . '/../lib/DoctrineBaseService.php';

class BorrowService extends DoctrineBaseService {	

	private $dateFormat = "Y-m-d";

	public function MigrateBorrowHistory()
	{
		$response = new Json\Commands\BorrowResponse();
		try {
			$borrowHistory = $this->doctrinemodel->createQueryBuilder('Models\BorrowHistory')
				->getQuery()
				->execute()
				->toArray();

				if ($borrowHistory != null) {
					$borrows = array();
					foreach ($borrowHistory as $borrowRecord) {
						$borrow_data = array(
							"username" => $borrowRecord->getUser()->getUsername(),
							'bookTag' => $borrowRecord->getBook()->GetBianHao(),
							'startBorrowDate' => $borrowRecord->getStartBorrowDate(),
							'planReturnDate' => $borrowRecord->getPlanReturnDate(),
							'realReturnDate' => $borrowRecord->getRealReturnDate()
							);
						$one_req = array("method" => "POST", "path" => "/1.1/classes/BorrowHistory", "body" => $borrow_data);
						array_push($borrows, $one_req);

						$requests = array("requests" => $borrows);
						$requests_string = json_encode($requests);
						$header = array(
					    	'X-AVOSCloud-Application-Id: jbxwg54yxbljq8onqtsdkptcbqs6wm0dt6gebmu7ixgdx3g9',
					    	'X-AVOSCloud-Application-Key: qn36ldhqtq1surxfp1i7bqj7l2c3a0nm5r37licqrdkzi7uf',
					    	'Content-Type: application/json','charset: utf-8');
					    $ch = curl_init();
					    curl_setopt($ch, CURLOPT_URL, 'https://leancloud.cn/1.1/batch');
					    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
					    curl_setopt($ch, CURLOPT_POSTFIELDS, $requests_string);
					    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
					    curl_setopt($ch, CURLOPT_VERBOSE, true);
					    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
					    $data = curl_exec($ch);
					    curl_close($ch);
						 $response->_returnMessage = json_encode($data);					
					}
				} else {
					$response->_returnCode = ErrorCode::NoSuchHistory;
				}
		} catch (Exception $e) {
			$response->_returnCode = ErrorCode::Failed;
			$response->_returnMessage = $e->getMessage ();
		}
		return $response;
	}
	
	public function GetAllHistory()
	{
		$response = new Json\Commands\BorrowResponse();
		
		try {
			$borrowHistory = $this->doctrinemodel->createQueryBuilder('Models\BorrowHistory')->getQuery()->execute()->toArray();
		
			if ($borrowHistory != NULL) {
				$response->_returnCode = ErrorCode::OK;
		
				$response->history = array();
		
				foreach ($borrowHistory as $borrowRecord) {
					array_push($response->history, new CBorrowHistory($borrowRecord));
				}
			}
			else {
				$response->_returnCode = ErrorCode::NoSuchHistory;
			}
		
		} catch ( Exception $e ) {
			$response->_returnCode = ErrorCode::Failed;
			$response->_returnMessage = $e->getMessage ();
		}
		return $response;
	}
	
	 public function Borrow($username,$bookBianhao)
	 {
	 	$response = new \Json\Commands\BorrowResponse();
	 	
	 	try
	 	{
	 		$user = $this->doctrinemodel->createQueryBuilder('Models\User')
			->field('username')->equals(new \MongoRegex('/^'.$username.'$/i'))
			->getQuery()
			->execute()
			->toArray();
	 		$book = $this->doctrinemodel->getRepository ( 'Models\Book' )->findOneBy ( array ('BianHao' => $bookBianhao) );
	 		
	 		if($user == null)
	 		{
	 			$response->_returnCode = ErrorCode::InvalidUser;
	 		}else if($book == null)
	 		{
	 			$response->_returnCode = ErrorCode::NoSuchBook;
	 		}else{
	 			$user = current($user);
               // check whether you have borrowed 3 books
                $borrowedResult = $this->doctrinemodel->getRepository ( 'Models\BorrowHistory' )
                        ->findBy(array('user.$id'=> new \MongoId($user->getId()), 'realReturnDate' => '-1'))->toArray();
                
                //die(var_dump($borrowedResult));
                if($borrowedResult != null && count($borrowedResult) >= 3)
                {
                    $response->_returnCode = ErrorCode::BorrowedBookExceed3;
                    return $response;
                }
                            
	 			//check whether the book is in borrowed status.
	 			$result = $this->doctrinemodel->getRepository ( 'Models\BorrowHistory' )
					->findOneBy ( array ('book.$id' => new \MongoId($book->getId()),'realReturnDate' => '-1') );
				if($result != NULL)
				{
					$response->_returnCode = ErrorCode::Failed;
					return $response;
				}
	 			
				//It can be borrowed
	 			$starBorrowDate = time();
	 			$planReturnDate = strtotime( '+1 month', $starBorrowDate );
	 			
	 			$borrow = new \Models\BorrowHistory($user, $book, Date($this->dateFormat,$starBorrowDate), Date($this->dateFormat,$planReturnDate));
	 			$this->doctrinemodel->persist($borrow);
	 			$this->doctrinemodel->flush();
	 			$response->_returnCode = ErrorCode::OK;
	 		}
	 		
	 	}catch ( Exception $e ) {
			$response->_returnCode = ErrorCode::Failed;
			$response->_returnMessage = $e->__toString ();
		}
		
		return $response;
	 }

	public function ReturnBook($username,$bookBianhao)
	{
		$response = new \Json\Commands\BorrowResponse();
		 
		try
		{
			$user = $this->doctrinemodel->createQueryBuilder('Models\User')
			->field('username')->equals(new \MongoRegex('/^'.$username.'$/i'))
			->getQuery()
			->execute()
			->toArray();			
			$book = $this->doctrinemodel->getRepository ( 'Models\Book' )->findOneBy ( array ('BianHao' => $bookBianhao) );
		
			if($user == null)
			{
				$response->_returnCode = ErrorCode::InvalidUser;
			}else if($book == null)
			{
				$response->_returnCode = ErrorCode::NoSuchBook;
			}else{
				$user = current($user);
				//check whether the book is in borrowed status.
	 			$result = $this->doctrinemodel->getRepository ( 'Models\BorrowHistory' )
					->findOneBy ( array ('book.$id' => new \MongoId($book->getId()),'realReturnDate' => '-1') );
				if($result == NULL)
				{
					$response->_returnCode = ErrorCode::Failed;
					return $response;
				}
				
				//It can be returned
				$realReturnDate = Date($this->dateFormat,time());		
				
				$this->doctrinemodel->createQueryBuilder('Models\BorrowHistory')
				->update()
				->field('realReturnDate')->set($realReturnDate)
				->field('user.$id')->equals(new \MongoId($user->getId()))
				->field('book.$id')->equals(new \MongoId($book->getId()))
				->field('realReturnDate')->equals('-1')
				->getQuery()
				->execute();				
				
				$response->_returnCode = ErrorCode::OK;
			}
		
		}catch ( Exception $e ) {
			$response->_returnCode = ErrorCode::Failed;
			$response->_returnMessage = $e->__toString ();
		}
		
		return $response;
	}
	
	function checkWhetherBookInBorrow($bookBianhao)
	{
		$response = new BaseResponse();
		
		try {
			$book = $this->doctrinemodel->getRepository ( 'Models\Book' )->findOneBy ( array ('BianHao' => $bookBianhao) );
			
			if ($book != NULL)
			{
				$result = $this->doctrinemodel->getRepository ( 'Models\BorrowHistory' )
				->findOneBy ( array ('book.$id' => new \MongoId($book->getId()),'realReturnDate' => '-1') );
				if($result != NULL)
				{
					$response->borrowHistory = new CBorrowHistory($result);
					$response->_returnCode = ErrorCode::OK;
				}
				else
				{
					$response->_returnCode = ErrorCode::NoSuchHistory;
				}
			}
			else 
			{
				$response->_returnCode = ErrorCode::NoSuchBook;
			}
		}
		catch ( Exception $e ) {
			$response->_returnCode = ErrorCode::Failed;
			$response->_returnMessage = $e->__toString ();
		}
				
		return $response;
			
	}
	
	function getBorrowInfo($username)
	{
		$response = new BorrowResponse();
		
		try {
			$user = $this->doctrinemodel->createQueryBuilder('Models\User')
			->field('username')->equals(new \MongoRegex('/^'.$username.'$/i'))
			->getQuery()
			->execute()
			->toArray();			
			
			if ($user != null) 
			{
				$user = current($user);
				$result = $this->doctrinemodel->createQueryBuilder('Models\BorrowHistory')
				->field('user.$id')->equals(new \MongoId($user->getId()))
				->field('realReturnDate')->equals('-1')
				->sort('startBorrowDate', -1)
				->getQuery()
				->execute()
				->toArray();
				
				if($result != NULL)
				{
					$response->_returnCode = ErrorCode::OK;
					$response->borrowInfo = array();
					foreach ($result as $borrowHistory) {
						array_push($response->borrowInfo, new CBorrowHistory($borrowHistory));
					}
				}else
				{
					$response->_returnCode = ErrorCode::NoBookInBorrow;
				}
			} else 
			{
				$response->_returnCode = ErrorCode::InvalidUser;
			}
		}
		catch ( Exception $e ) {
			$response->_returnCode = ErrorCode::Failed;
			$response->_returnMessage = $e->__toString ();
		}
		
		return $response;
	}

	function getBorrowedInfo($username)
	{
		$response = new BorrowResponse();
		try {
			$user = $this->doctrinemodel->createQueryBuilder('Models\User')
			->field('username')->equals(new \MongoRegex('/^'.$username.'$/i'))
			->getQuery()
			->execute()
			->toArray();			
			if ($user != null)
			{
				$user = current($user);
				$result = $this->doctrinemodel->createQueryBuilder('Models\BorrowHistory')
				->field('user.$id')->equals(new \MongoId($user->getId()))
				->field('realReturnDate')->notEqual('-1')
				->sort('realReturnDate', -1)
				->getQuery()
				->execute()
				->toArray();

				if ($result != null)
				{
					$response->_returnCode = ErrorCode::OK;
					$response->borrowInfo = array();
					foreach ($result as $borrowHistory) {
						array_push($response->borrowInfo, new CBorrowHistory($borrowHistory));
					}
				}
				else
				{
					$response->_returnCode = ErrorCode::NoBookInBorrow;
				}
			} else 
			{
				$response->_returnCode = ErrorCode::InvalidUser;
			}
		}
		catch ( Exception $e ) {
			$response->_returnCode = ErrorCode::Failed;
			$response->_returnMessage = $e->__toString ();
		}

		return $response;
	}
	
	function RemoveAll()
	{
		$response = new BaseResponse();
	
		try {
			$result = $this->doctrinemodel->getRepository ( 'Models\BorrowHistory' )->findAll()->toArray();
			if($result != NULL)
			{
				foreach ($result as $row)
				{
					$this->doctrinemodel->remove($row);
				}
				$this->doctrinemodel->flush();
				$response->_returnCode = ErrorCode::OK;
			}
		}
		catch ( Exception $e ) {
			$response->_returnCode = ErrorCode::Failed;
			$response->_returnMessage = $e->__toString ();
		}
	
		return $response;
	
	}
}
?>	

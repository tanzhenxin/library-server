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
	 		$user = $this->doctrinemodel->getRepository ( 'Models\User' )->findOneBy ( array ('username' => $username ) );
	 		$book = $this->doctrinemodel->getRepository ( 'Models\Book' )->findOneBy ( array ('BianHao' => $bookBianhao) );
	 		
	 		if($user == null)
	 		{
	 			$response->_returnCode = ErrorCode::InvalidUser;
	 		}else if($book == null)
	 		{
	 			$response->_returnCode = ErrorCode::NoSuchBook;
	 		}else{
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
			$user = $this->doctrinemodel->getRepository ( 'Models\User' )->findOneBy ( array ('username' => $username ) );
			$book = $this->doctrinemodel->getRepository ( 'Models\Book' )->findOneBy ( array ('BianHao' => $bookBianhao) );
		
			if($user == null)
			{
				$response->_returnCode = ErrorCode::InvalidUser;
			}else if($book == null)
			{
				$response->_returnCode = ErrorCode::NoSuchBook;
			}else{
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
			$user = $this->doctrinemodel->getRepository ( 'Models\User' )->findOneBy ( array ('username' => $username ) );
			
			$result = $this->doctrinemodel->getRepository ( 'Models\BorrowHistory' )
			->findBy ( array ('user.$id' => new \MongoId($user->getId()),'realReturnDate' => '-1') );
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

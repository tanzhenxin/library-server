<?php
use Json\Data\CBook;

use Json\Commands\BookResponse;

use Json\Commands\BaseResponse;
use Constant\ErrorCode;
use Models\Book;
include_once __DIR__ . '/../lib/DoctrineBaseService.php';

class BookService extends DoctrineBaseService {	
	
	 /* Get All the books in the Library
	 * @return BookResponse
	 */
	function GetAllBooks($offset, $count) {
		
		$response = new Json\Commands\BookResponse();
		
		try {
			if ($offset != null && $count != null)
			{
				$allBooks = $this->doctrinemodel->createQueryBuilder('Models\Book')
				->skip($offset)
				->limit($count)
				->getQuery()
				->execute()
				->toArray();
			}
			else 
			{
				$allBooks = $this->doctrinemodel->createQueryBuilder('Models\Book')
				->getQuery()
				->execute()
				->toArray();
			}
			
			if ($allBooks != NULL) {
				$response->_returnCode = ErrorCode::OK;
				
				$response->Books = array();
				
				foreach ($allBooks as $book) {
					array_push($response->Books, new CBook($book));
				}
			}	
			else {
				$response->_returnCode = ErrorCode::CannotGetBookList; 
			}
		
		} catch ( Exception $e ) {
			$response->_returnCode = ErrorCode::Failed;
			$response->_returnMessage = $e->getMessage ();
		}
		return $response;
	}

	/**
	 * 
	 * @param string $bianHao
	 * @param string $title
	 * @param string $author
	 * @param string $publisher
	 * @param string $publishedDate
	 * @param string $language
	 * @param int $printLength
	 * @param string $ISBN
	 * @param string $price
	 * @param string $description
	 * @param string $imageUrl
	 * @return \Json\Commands\BookResponse
	 */
	function AddBook($bianHao,$title,$author,$publisher,$publishedDate,$language,$printLength,$ISBN,$price,$description,$imageUrl)
	{
		$response = new BookResponse();
		
		try {
			$result = $this->doctrinemodel->getRepository ( 'Models\Book' )->findOneBy ( array ('BianHao' => $bianHao ) );
			if($result != NULL)
			{
				$response->_returnCode = ErrorCode::BianHaoAlreadyExists;
			}else
			{
				$book = new Book($bianHao, $title,$author);
				$book->setPublisher($publisher);
				$book->setPublishedDate($publishedDate);
				$book->setLanguage($language);
				$book->setPrintLength($printLength);
				$book->setISBN($ISBN);
				$book->setPrice($price);
				$book->SetDescription($description);
					
				$this->doctrinemodel->persist($book);
				$this->doctrinemodel->flush();
				
				// Create a pic on the server side under gtcclibrary/Images
				// Image name is the ISBN.jpg
				if(!empty($imageUrl))
				{
					$img = file_get_contents($imageUrl); 
					
					$imageName = __DIR__.'/../../Images/'.$ISBN.'.jpg';
					
					if(!file_exists($imageName))
					{				
						//echo $imageName;
						file_put_contents($imageName,$img); 
					}
				}
				
				$response->_returnCode = ErrorCode::OK;
				$response->book = $book;
			}
		}
		catch ( Exception $e ) {
			$response->_returnCode = ErrorCode::Failed;
			$response->_returnMessage = $e->__toString ();
		}
		
		return $response;
	}

	/**
	 * 
	 * @param unknown_type $bianhao
	 * @return \Json\Commands\BookResponse
	 */
	function RemoveBook($bianhao)
	{
		$response = new BookResponse();
		
		try {
			$result = $this->doctrinemodel->getRepository ( 'Models\Book' )->findOneBy ( array ('BianHao' => $bianhao ) );
			if($result != NULL)
			{
				$this->doctrinemodel->remove($result);
				$this->doctrinemodel->flush();
				$response->_returnCode = ErrorCode::OK;
			}else
			{
			
				$response->_returnCode = ErrorCode::NoSuchBook;
				$response->_returnMessage = "No Such Book";
			}
		}
		catch ( Exception $e ) {
			$response->_returnCode = ErrorCode::Failed;
			$response->_returnMessage = $e->__toString ();
		}
		
		return $response;
	}
	
	/**
	 * 
	 * @param unknown_type $bianhao
	 * @param unknown_type $author
	 * @param unknown_type $description
	 * @return \Json\Commands\BookResponse
	 */
	function EditBook($bianhao, $description = NULL)
	{
		$response = new BookResponse();
		
		try {
			$result = $this->doctrinemodel->getRepository ( 'Models\Book' )->findOneBy ( array ('BianHao' => $bianhao ) );
			if($result == NULL)
			{
				$response->_returnCode = ErrorCode::NoSuchBook;
			}else
			{
				
				$this->doctrinemodel->createQueryBuilder('Models\Book')
				->update()
				->field('description')->set($description)
				->field('BianHao')->equals($bianhao)
				->getQuery()
				->execute();
		
				$response->_returnCode = ErrorCode::OK;
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
			$result = $this->doctrinemodel->getRepository ( 'Models\Book' )->findAll()->toArray();
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
	
	function GetBookByBianHao($bianhao)
	{
		$response = new BookResponse();
		
		try {
			$result = $this->doctrinemodel->getRepository ( 'Models\Book' )->findOneBy ( array ('BianHao' => $bianhao ) );
			if($result == NULL)
			{
				$response->_returnCode = ErrorCode::NoSuchBook;
			}
			else
			{
				$response->book = new CBook($result);
				$response->_returnCode = ErrorCode::OK;
			}
		}
		catch ( Exception $e ) {
			$response->_returnCode = ErrorCode::Failed;
			$response->_returnMessage = $e->__toString ();
		}
		
		return $response;
	}
	
	function GetBookByISBN($ISBN)
	{
		$response = new BookResponse();
		
		try {
			$result = $this->doctrinemodel->getRepository ( 'Models\Book' )->findOneBy ( array ('ISBN' => $ISBN.'' ) );
			if($result == NULL)
			{
				$response->_returnCode = ErrorCode::NoSuchBook;
			}
			else
			{
				$response->book = new CBook($result);
				$response->_returnCode = ErrorCode::OK;
			}
		}
		catch ( Exception $e ) {
			$response->_returnCode = ErrorCode::Failed;
			$response->_returnMessage = $e->__toString ();
		}
		
		return $response;
	}
	
	function GetAllBooksInList($offset, $count) {
		
		$response = new Json\Commands\BookResponse();
		
		try {
			if ($offset != null && $count != null)
			{
				$allBooks = $this->doctrinemodel->createQueryBuilder('Models\Book')
				->select('title', 'BianHao', 'ISBN', 'author', 'publisher', 'publishedDate', 'price')
				->skip($offset)
				->limit($count)
				->getQuery()
				->execute()
				->toArray();
			}
			else 
			{
				$allBooks = $this->doctrinemodel->createQueryBuilder('Models\Book')
				->select('title', 'BianHao', 'ISBN', 'author', 'publisher', 'publishedDate', 'price')
				->getQuery()
				->execute()
				->toArray();
			}
			
			if ($allBooks != NULL) {
				$response->_returnCode = ErrorCode::OK;
				
				$response->Books = array();
					
				foreach ($allBooks as $book) {
					array_push($response->Books, new CBook($book));
				}
			}	
			else {
				$response->_returnCode = ErrorCode::CannotGetBookList; 
			}
		
		} catch ( Exception $e ) {
			$response->_returnCode = ErrorCode::Failed;
			$response->_returnMessage = $e->getMessage ();
		}
		return $response;
	}

	function GetAllBooksByCategory($category, $offset, $count) {
		$response = new Json\Commands\BookResponse();
		
		try {
			if ($offset != null && $count != null)
			{
				$allBooks = $this->doctrinemodel->createQueryBuilder('Models\Book')
				->select('title', 'BianHao', 'ISBN', 'author', 'publisher', 'publishedDate', 'price')
				->skip($offset)
				->limit($count)
				->field('BianHao')->where("function() { return this.BianHao.startsWith(\"".$category."\"); }")
				->getQuery()
				->execute()
				->toArray();
			}
			else 
			{
				$allBooks = $this->doctrinemodel->createQueryBuilder('Models\Book')
				->select('title', 'BianHao', 'ISBN', 'author', 'publisher', 'publishedDate', 'price')
				->field('BianHao')->where("function() { return this.BianHao.startsWith(\"".$category."\"); }")
				->getQuery()
				->execute()
				->toArray();
			}
			
			if ($allBooks != NULL) {
				$response->_returnCode = ErrorCode::OK;
				
				$response->Books = array();
					
				foreach ($allBooks as $book) {
					array_push($response->Books, new CBook($book));
				}
			}	
			else {
				$response->_returnCode = ErrorCode::CannotGetBookList; 
			}
		
		} catch ( Exception $e ) {
			$response->_returnCode = ErrorCode::Failed;
			$response->_returnMessage = $e->getMessage ();
		}
		return $response;
	}

	function SearchBooks($title, $offset, $count) {
		$response = new Json\Commands\BookResponse();
		
		try {
			if ($offset != null && $count != null)
			{
				$allBooks = $this->doctrinemodel->createQueryBuilder('Models\Book')
				->select('title', 'BianHao', 'ISBN', 'author', 'publisher', 'publishedDate', 'price')
				->skip($offset)
				->limit($count)
				->field('title')->equals(new \MongoRegex('/.*'.$title.'.*/i'))
				->getQuery()
				->execute()
				->toArray();
			}
			else 
			{
				$allBooks = $this->doctrinemodel->createQueryBuilder('Models\Book')
				->select('title', 'BianHao', 'ISBN', 'author', 'publisher', 'publishedDate', 'price')
				->field('title')->equals(new \MongoRegex('/.*'.$title.'.*/i'))
				->getQuery()
				->execute()
				->toArray();
			}
			
			if ($allBooks != NULL) {
				$response->_returnCode = ErrorCode::OK;
				
				$response->Books = array();
					
				foreach ($allBooks as $book) {
					array_push($response->Books, new CBook($book));
				}
			}	
			else {
				$response->_returnCode = ErrorCode::CannotGetBookList; 
			}
		
		} catch ( Exception $e ) {
			$response->_returnCode = ErrorCode::Failed;
			$response->_returnMessage = $e->getMessage ();
		}
		return $response;
	}
}
?>	

<?php
use Json\Data\CBook;

use Json\Commands\BookResponse;

use Json\Commands\BaseResponse;
use Constant\ErrorCode;
use Models\Book;
include_once __DIR__ . '/../lib/DoctrineBaseService.php';

class BookService extends DoctrineBaseService {	

	function MigrateAllBooks($offset, $count) {
		$response = new Json\Commands\BookResponse();

		try {
			if ($offset != null && $count != null) {
				$allBooks = $this->doctrinemodel->createQueryBuilder('Models\Book')
				->skip($offset)
				->limit($count)
				->getQuery()
				->execute()
				->toArray();
			} else {
				$allBooks = $this->doctrinemodel->createQueryBuilder('Models\Book')
				->getQuery()
				->execute()
				->toArray();
			}

			if ($allBooks != null) {
				$books = array();
				foreach ($allBooks as $book) {
					$book_data = array(
						"tag" => $book->GetBianHao(),
						"title" => $book->GetTitle(),
						"description" => $book->GetDescription(),
						"author" => $book->GetAuthor(),
						"publisher" => $book->GetPublisher(),
						"publishedDate" => $book->GetPublishedDate(),
						"printLength" => $book->GetPrintLength(),
						"ISBN" => $book->getISBN(),
						"price" => $book->getPrice()
						);
					$one_req = array("method" => "POST", "path" => "/1.1/classes/Book", "body" => $book_data);
					array_push($books, $one_req);
				}

				$requests = array("requests" => $books);
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
		} catch (Exception $e) {
			$response->_returnCode = ErrorCode::Failed;
			$response->_returnMessage = $e->getMessage ();
		}

		return $response;
	}
	
	 /* Get All the books in the Library
	 * @return BookResponse
	 */
	function GetAllBooks($offset, $count) {
		
		$response = new Json\Commands\BookRespones();
		
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
			$result = $this->doctrinemodel->getRepository ( 'Models\Book' )->findOneBy ( array ('BianHao' => strtoupper(trim($bianHao))) );
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
					$img = base64_decode($imageUrl); 
					
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
			$result = $this->doctrinemodel->getRepository ( 'Models\Book' )->findOneBy ( array ('BianHao' => strtoupper(trim($bianhao)) ) );
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
			$result = $this->doctrinemodel->getRepository ( 'Models\Book' )->findOneBy ( array ('BianHao' => strtoupper(trim($bianhao)) ) );
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
        
        function GetBookListByISBN($ISBN)
	{
		$response = new BookResponse();
		
		try {
			$result = $this->doctrinemodel->getRepository ( 'Models\Book' )
                                                    ->findBy( array ('ISBN' => $ISBN.'' ) )
                                                    ->toArray();
			if($result != NULL)
			{
                            $response->_returnCode = ErrorCode::OK;
				
				$response->BookList = array();
				
				foreach ($result as $book) {
					array_push($response->BookList, new CBook($book));
				}
			}
			else
			{
				$response->_returnCode = ErrorCode::Failed;
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
				->field('BianHao')->equals(new \MongoRegex('/'.$category.'.*/i'))
				->sort('title')
				->getQuery()
				->execute()
				->toArray();
			}
			else 
			{
				$allBooks = $this->doctrinemodel->createQueryBuilder('Models\Book')
				->select('title', 'BianHao', 'ISBN', 'author', 'publisher', 'publishedDate', 'price')
				->field('BianHao')->equals(new \MongoRegex('/'.$category.'.*/i'))
				->sort('title')
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
				->sort('title')
				->getQuery()
				->execute()
				->toArray();
			}
			else 
			{
				$allBooks = $this->doctrinemodel->createQueryBuilder('Models\Book')
				->select('title', 'BianHao', 'ISBN', 'author', 'publisher', 'publishedDate', 'price')
				->field('title')->equals(new \MongoRegex('/.*'.$title.'.*/i'))
				->sort('title')
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
        
        function UpdateBookTag()
        {
            $updateTagList = array(
                'D-149'=>'E-001',
'D-075'=>'E-002',
'A-088'=>'E-003',
'D-132'=>'E-004',
'D-002'=>'E-005',
'D-006'=>'E-006',
'D-205'=>'E-007',
'D-009'=>'E-009',
'D-226'=>'E-010',
'A-063'=>'E-011',
'A-065'=>'E-012',
'A-022'=>'E-013',
'D-081'=>'E-014',
'D-218'=>'E-015',
'D-032'=>'E-016',
'D-092'=>'E-017',
'D-188'=>'E-018',
'D-098'=>'E-019',
'D-223'=>'E-020',
'D-217'=>'E-021',
'D-071'=>'E-022',
'D-033'=>'E-023',
'D-011'=>'E-024',
'D-005'=>'E-025',
'D-133'=>'E-026',
'D-216'=>'E-027',
'D-100'=>'E-028',
'D-112'=>'E-029',
'D-093'=>'E-030',
'D-097'=>'E-031',
'D-003'=>'E-032',
'D-113'=>'E-033',
'D-073'=>'E-034',
'D-020'=>'E-035',
'D-022'=>'E-036',
'A-025'=>'E-037',
'D-090'=>'E-038',
'B-020'=>'F-001',
'B-018'=>'F-002',
'B-019'=>'F-004',
'B-016'=>'F-005',
'B-003'=>'F-006',
'B-005'=>'F-007',
'B-021'=>'F-008',
'B-004'=>'F-009',
'B-012'=>'F-010',
'B-013'=>'F-011',
'B-014'=>'F-012',
'B-015'=>'F-013',
'B-001'=>'F-014',
'B-008'=>'F-015',
'B-009'=>'F-016',
'B-007'=>'F-017',
'B-006'=>'F-018',
'B-022'=>'F-019',
'B-017'=>'F-020',
'B-010'=>'F-021',
'B-002'=>'F-022',
'A-037'=>'M-001',
'A-062'=>'M-002',
'A-003'=>'M-003',
'D-222'=>'M-004',
'A-075'=>'M-005',
'A-073'=>'M-006',
'A-018'=>'M-008',
'D-220'=>'M-009',
'A-021'=>'M-010',
'A-020'=>'M-011',
'A-068'=>'M-012',
'A-006'=>'M-013',
'A-059'=>'M-014',
'A-014'=>'M-015',
'D-024'=>'M-016',
'A-011'=>'M-017',
'C-006'=>'M-018',
'A-060'=>'M-019',
'D-186'=>'M-020',
'A-030'=>'M-021',
'A-029'=>'M-022',
'A-051'=>'M-023',
'D-211'=>'M-024',
'A-083'=>'M-025',
'A-086'=>'M-026',
'A-066'=>'M-027',
'D-156'=>'M-028',
'A-040'=>'M-029',
'A-041'=>'M-030',
'A-031'=>'M-031',
'A-035'=>'M-032',
'A-067'=>'M-033',
'A-047'=>'S-001',
'A-002'=>'S-002',
'A-069'=>'S-003',
'A-071'=>'S-004',
'A-098'=>'S-005',
'A-042'=>'S-006',
'A-008'=>'S-007',
'A-080'=>'S-008',
'A-054'=>'S-009',
'A-034'=>'S-010',
'A-078'=>'S-011',
'A-061'=>'S-013',
'B-011'=>'S-014',
'A-089'=>'S-015',
'A-028'=>'S-017',
'A-049'=>'S-018',
'A-050'=>'S-019',
'A-010'=>'S-020',
'A-013'=>'S-021',
'A-027'=>'S-022',
'A-092'=>'S-023',
'A-036'=>'S-024',
'A-095'=>'S-025',
'A-093'=>'S-026',
'A-043'=>'S-027',
'A-090'=>'S-028',
'A-091'=>'S-029',
'A-079'=>'S-030',
'A-005'=>'S-031',
'A-082'=>'S-032',
'A-007'=>'S-033',
'A-084'=>'S-034',
'C-040'=>'S-035',
'A-097'=>'S-036',
'A-046'=>'S-037',
'A-081'=>'S-038',
'A-044'=>'S-039',
'A-096'=>'S-040',
'A-048'=>'S-041',
'A-058'=>'S-042',
'A-052'=>'S-043',
'A-094'=>'S-044',
'A-004'=>'S-045',
'A-024'=>'S-046',
'A-076'=>'S-047',
'A-099'=>'S-048',
'A-026'=>'S-049',
'A-085'=>'S-050',
'A-045'=>'S-051',
'A-033'=>'S-052',
'A-032'=>'S-053',
'A-038'=>'S-054',
'A-039'=>'S-055',
'A-087'=>'S-056',
'A-009'=>'S-057',
'A-012'=>'S-058',
'D-077'=>'T-001',
'D-056'=>'T-002',
'D-052'=>'T-003',
'D-067'=>'T-004',
'D-118'=>'T-005',
'D-115'=>'T-006',
'D-181'=>'T-007',
'D-168'=>'T-008',
'D-197'=>'T-009',
'D-025'=>'T-010',
'D-167'=>'T-011',
'D-049'=>'T-012',
'D-121'=>'T-013',
'D-134'=>'T-014',
'D-204'=>'T-015',
'D-202'=>'T-016',
'D-126'=>'T-017',
'D-129'=>'T-018',
'D-058'=>'T-019',
'D-198'=>'T-020',
'D-207'=>'T-021',
'D-040'=>'T-022',
'D-066'=>'T-023',
'D-170'=>'T-024',
'D-210'=>'T-025',
'D-012'=>'T-026',
'D-027'=>'T-027',
'D-064'=>'T-028',
'D-143'=>'T-029',
'D-041'=>'T-030',
'D-059'=>'T-031',
'D-139'=>'T-032',
'D-184'=>'T-033',
'D-086'=>'T-034',
'D-183'=>'T-035',
'D-085'=>'T-036',
'D-105'=>'T-037',
'D-048'=>'T-038',
'D-062'=>'T-039',
'D-208'=>'T-040',
'D-060'=>'T-041',
'D-185'=>'T-042',
'D-182'=>'T-043',
'D-209'=>'T-044',
'D-036'=>'T-045',
'D-050'=>'T-046',
'D-055'=>'T-047',
'D-137'=>'T-048',
'D-224'=>'T-049',
'D-135'=>'T-050',
'D-007'=>'T-051',
'D-072'=>'T-052',
'D-074'=>'T-053',
'D-087'=>'T-054',
'D-145'=>'T-055',
'D-146'=>'T-056',
'D-019'=>'T-057',
'D-153'=>'T-058',
'D-103'=>'T-059',
'D-151'=>'T-060',
'D-010'=>'T-061',
'D-125'=>'T-062',
'D-155'=>'T-063',
'D-094'=>'T-064',
'D-136'=>'T-065',
'D-154'=>'T-066',
'D-190'=>'T-067',
'D-122'=>'T-068',
'D-116'=>'T-069',
'D-131'=>'T-070',
'D-177'=>'T-071',
'D-075'=>'T-072',
'D-099'=>'T-073',
'D-045'=>'T-074',
'D-159'=>'T-075',
'D-030'=>'T-076',
'D-158'=>'T-077',
'D-015'=>'T-078',
'D-230'=>'T-079',
'D-051'=>'T-080',
'D-104'=>'T-081',
'D-163'=>'T-082',
'D-008'=>'T-083',
'D-031'=>'T-084',
'D-108'=>'T-085',
'D-140'=>'T-086',
'D-138'=>'T-087',
'D-096'=>'T-088',
'D-215'=>'T-089',
'D-175'=>'T-090',
'D-068'=>'T-091',
'D-171'=>'T-092',
'D-194'=>'T-093',
'D-180'=>'T-094',
'D-192'=>'T-095',
'D-191'=>'T-096',
'D-017'=>'T-097',
'D-203'=>'T-098',
'D-083'=>'T-099',
'D-219'=>'T-100',
'D-110'=>'T-101',
'D-034'=>'T-102',
'D-102'=>'T-103',
'D-101'=>'T-104',
'D-212'=>'T-105',
'D-176'=>'T-106',
'D-147'=>'T-107',
'D-173'=>'T-108',
'D-028'=>'T-109',
'D-043'=>'T-110',
'D-044'=>'T-111',
'D-196'=>'T-112',
'D-162'=>'T-113',
'D-078'=>'T-114',
'D-070'=>'T-115',
'D-123'=>'T-116',
'D-084'=>'T-117',
'D-111'=>'T-118',
'D-029'=>'T-119',
'D-088'=>'T-120',
'D-054'=>'T-121',
'D-063'=>'T-122',
'D-144'=>'T-123',
'D-227'=>'T-125',
'D-161'=>'T-126',
'D-057'=>'T-127',
'D-047'=>'T-128',
'D-053'=>'T-129',
'D-080'=>'T-130',
'D-189'=>'T-131',
'D-214'=>'T-132',
'D-164'=>'T-133',
'D-228'=>'T-134',
'D-169'=>'T-135',
'D-195'=>'T-136',
'D-130'=>'T-137',
'D-018'=>'T-139',
'D-213'=>'T-140',
'D-200'=>'T-141',
'D-026'=>'T-142',
'D-042'=>'T-143',
'D-193'=>'T-144',
'D-061'=>'T-145',
'D-091'=>'T-146',
'D-065'=>'T-147',
'D-109'=>'T-148',
'D-231'=>'T-149',
'D-152'=>'T-150',
'D-201'=>'T-151',
'D-014'=>'T-152',
'D-035'=>'T-153',
'D-038'=>'T-154',
'D-107'=>'T-155',
'D-187'=>'T-156',
'D-117'=>'T-157',
'D-142'=>'T-159',
'D-221'=>'T-160',
'D-166'=>'T-161',
'D-148'=>'T-162',
'D-160'=>'T-163',
'D-225'=>'T-164',
'D-229'=>'T-165',
'D-165'=>'T-166',
'D-150'=>'T-167',
'D-199'=>'T-168',
'D-082'=>'T-169',
'D-157'=>'T-171',
'D-046'=>'T-172',
'D-079'=>'T-173',
'D-124'=>'T-174',
'D-174'=>'T-175',
'D-076'=>'T-176',
'D-141'=>'T-177',
'D-095'=>'T-178',
'D-178'=>'T-179',
'D-120'=>'T-180',
'D-172'=>'T-181',
'D-179'=>'T-182',
'D-206'=>'T-183',
'D-127'=>'T-184',
'D-106'=>'T-185',
'D-128'=>'T-186',
'D-119'=>'T-187',
'D-016'=>'T-188',
'D-023'=>'T-189',
'D-004'=>'T-190',
'D-039'=>'T-191',
'D-021'=>'T-193',
'D-232'=>'T-194',
'D-037'=>'T-195',
'D-069'=>'T-196',
'C-038'=>'Z-001',
'A-001'=>'Z-002',
'C-003'=>'Z-003',
'D-001'=>'Z-004',
'C-004'=>'Z-005',
'C-008'=>'Z-006',
'C-028'=>'Z-007',
'C-029'=>'Z-008',
'C-030'=>'Z-009',
'A-057'=>'Z-010',
'C-014'=>'Z-011',
'C-027'=>'Z-012',
'C-035'=>'Z-013',
'A-056'=>'Z-014',
'C-009'=>'Z-015',
'D-013'=>'Z-016',
'C-036'=>'Z-017',
'A-055'=>'Z-018',
'A-070'=>'Z-019',
'C-010'=>'Z-020',
'C-002'=>'Z-021',
'C-001'=>'Z-022',
'C-042'=>'Z-023',
'C-037'=>'Z-024',
'A-016'=>'Z-025',
'C-005'=>'Z-026',
'C-025'=>'Z-027',
'C-024'=>'Z-028',
'A-019'=>'Z-029',
'C-031'=>'Z-030',
'C-032'=>'Z-031',
'C-017'=>'Z-032',
'A-074'=>'Z-033',
'A-017'=>'Z-034',
'C-041'=>'Z-035',
'C-015'=>'Z-036',
'C-039'=>'Z-037',
'C-012'=>'Z-038',
'A-064'=>'Z-039',
'D-114'=>'Z-040',
'C-016'=>'Z-041',
'C-026'=>'Z-042',
'C-022'=>'Z-043',
'A-077'=>'Z-044',
'A-015'=>'Z-045',
'A-072'=>'Z-046',
'A-053'=>'Z-047',
'C-033'=>'Z-048',
'C-013'=>'Z-049',
'C-018'=>'Z-050',
'C-021'=>'Z-051',
'C-019'=>'Z-052',
'C-020'=>'Z-053',
'C-007'=>'Z-054',
'C-023'=>'Z-055',
'C-011'=>'Z-056',
'C-034'=>'Z-057',
'A-023'=>'Z-058',


            );
            
            // get all the book
            $allBooks = $this->doctrinemodel->createQueryBuilder('Models\Book')
				->getQuery()
				->execute()
				->toArray();
            
            foreach ($allBooks as $book) {
                $oldTag = $book->GetBianHao();
                $newTag = $updateTagList[$oldTag];
                $this->doctrinemodel->createQueryBuilder('Models\Book')
                    ->update()
                    ->field('BianHao')->set($newTag)
                    ->field('BianHao_old')->set($oldTag)
                    ->field('BianHao')->equals($oldTag)
                    ->getQuery()
                    ->execute();
            }            
            
        }
}
?>	

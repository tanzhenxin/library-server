<?php

class ApiController extends Controller
{
	private $format = 'json';

	public function filters()
	{
		return array();
	}

	public function actionIndex()
	{
		switch ($_GET['model'])
		{
			case 'books':
				$models = Book::model()->findAll();
				break;
			default:
				$this->_sendResponse(501, sprintf('Error: Mode <b>index</b> is not implemented for model <b>%s</b>',$_GET['model']));
		}
		if (is_null($models))
		{
			$this->_sendResponse(200, sprintf('No items were found for model <b>%s</b>', $_GET['model']));
		}
		else
		{
			$rows = array();
			foreach ($models as $model) {
				$rows[] = $model->attributes;
			}

			$this->_sendResponse(200, CJSON::encode($rows));
		}
	}

	public function actionView()
	{
		if (!isset($_GET['id']))
		{
			$this->_sendResponse(400, 'Error: parameter <b>id</b> is missing.');
		}

		switch ($_GET['model']) {
			case 'book':
				$model = Book::model()->findByPk(new MongoId($_GET['id']));
				break;
			default:
				$this->_sendResponse(501, sprintf('Error: Mode <b>view</b> is not implemented for model <b>%s</b>',$_GET['model']));
		}
		if (is_null($model))
		{
			$this->_sendResponse(404, 'No item was found with id ' . $_GET['id']);
		}
		else
		{
			$this->_sendResponse(200, $this->_getObjectEncoded($_GET['model'], $model->attributes));
		}
	}

    public function actionCreate()
    {
        switch ($_GET['model']) {
            case 'user': {
                foreach ($_POST as $key => $value) {
                    echo "$key: $value\n";
                }
                if (!isset($_POST['Username']) || !isset($_POST['Password']) || !isset($_POST['Email'])) {
                    $this->_sendResponse(400, 'Parameter is not complete. Check if Username, Password and Email are provided');
                }
                $username = $_POST['Username'];
                $model = User::model()->findByAttributes(array('username' => $username));
                if (!is_null($model)) {
                    $this->_sendResponse(400);
                }
                $model = new User;
                break;
            }
            default:
                $this->_sendResponse(501, sprintf('Mode <b>create</b> is not implemented for model <b>%s</b>', $_GET['model']));
                exit;
        }
        foreach ($_POST as $key => $value) {
            if ($model->hasAttribute($key)) {
                $model->$key = $value;
            } else {
                $this->_sendResponse(400, sprintf('Parameter <b>%s</b> is not allowed for model <b>%s</b>', $key, $_GET['model']));
            }
        }

        if ($model->save()) {
            $this->_sendResponse(200, $this->_getObjectEncoded($_GET['model'], $model->attributes));
        } else {
                        // Errors occurred
            $msg = "<h1>Error</h1>";
            $msg .= sprintf("Couldn't create model <b>%s</b>", $_GET['model']);
            $msg .= "<ul>";
            foreach($model->errors as $attribute=>$attr_errors) {
                $msg .= "<li>Attribute: $attribute</li>";
                $msg .= "<ul>";
                foreach($attr_errors as $attr_error) {
                    $msg .= "<li>$attr_error</li>";
                }        
                $msg .= "</ul>";
            }
            $msg .= "</ul>";
            $this->_sendResponse(500, $msg );
        }        
    }

	private function _sendResponse($status = 200, $body = '', $content_type = 'text/html')
	{
		$status_header = 'HTTP/1.1' . $status . ' ' . $this->_getStatusMessage($status);
		header($status_header);
		header('Content_type: ' . $content_type);

		if ($body != '')
		{
			echo $body;
			exit;
		}
		else
		{
			// create some body messages
            $message = '';

            // this is purely optional, but makes the pages a little nicer to read
            // for your users.  Since you won't likely send a lot of different status codes,
            // this also shouldn't be too ponderous to maintain
            switch($status)
            {
                case 401:
                    $message = 'You must be authorized to view this page.';
                    break;
                case 404:
                    $message = 'The requested URL ' . $_SERVER['REQUEST_URI'] . ' was not found.';
                    break;
                case 500:
                    $message = 'The server encountered an error processing your request.';
                    break;
                case 501:
                    $message = 'The requested method is not implemented.';
                    break;
            }

            // servers don't always have a signature turned on (this is an apache directive "ServerSignature On")
            $signature = ($_SERVER['SERVER_SIGNATURE'] == '') ? $_SERVER['SERVER_SOFTWARE'] . ' Server at ' . $_SERVER['SERVER_NAME'] . ' Port ' . $_SERVER['SERVER_PORT'] : $_SERVER['SERVER_SIGNATURE'];

            // this should be templatized in a real-world solution
            $body = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
                        <html>
                            <head>
                                <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
                                <title>' . $status . ' ' . $this->_getStatusMessage($status) . '</title>
                            </head>
                            <body>
                                <h1>' . $this->_getStatusMessage($status) . '</h1>
                                <p>' . $message . '</p>
                                <hr />
                                <address>' . $signature . '</address>
                            </body>
                        </html>';

            echo $body;
            exit;			
		}
	}

	private function _getStatusMessage($status)
	{
		$codes = array(
			100 => 'Continue',
            101 => 'Switching Protocols',
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative Information',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',
            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Found',
            303 => 'See Other',
            304 => 'Not Modified',
            305 => 'Use Proxy',
            306 => '(Unused)',
            307 => 'Temporary Redirect',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required',
            408 => 'Request Timeout',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Request Entity Too Large',
            414 => 'Request-URI Too Long',
            415 => 'Unsupported Media Type',
            416 => 'Requested Range Not Satisfiable',
            417 => 'Expectation Failed',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Timeout',
            505 => 'HTTP Version Not Supported'
		);
		return isset($codes[$status]) ? $codes[$status] : '';
	}

	private function _getObjectEncoded($model, $array)
    {
        if(isset($_GET['format']))
            $this->format = $_GET['format'];

        if($this->format=='json')
        {
            return CJSON::encode($array);
        }
        elseif($this->format=='xml')
        {
            $result = '<?xml version="1.0">';
            $result .= "\n<$model>\n";
            foreach($array as $key=>$value)
                $result .= "    <$key>".utf8_encode($value)."</$key>\n"; 
            $result .= '</'.$model.'>';
            return $result;
        }
        else
        {
            return;
        }
    } // }}} 
}
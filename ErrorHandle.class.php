<?php


    class ErrorHandle {

        protected $output_format = 'json';

        public function __construct () {
            
            //系统设置
            ini_set( "display_errors", 0 );     
            ini_set( "memory_limit", "128M" );
            ini_set( "date.timezone", "PRC" );

            //错误和异常捕获
            set_error_handler(array($this,'_error_handler'));
            set_exception_handler(array($this,'_exception_handler'));
            register_shutdown_function(array($this,'_shutdown_handler'));
        }


        public function _error_handler ( $errno, $errstr, $errfile, $errline ) {
            $message = 'errno: '.$errno.'  --> '.$errstr. ' '.$errfile.' '.$errline;
            switch ( $errno ) {
                case E_ERROR:
                case E_PARSE:
                case E_CORE_ERROR:
                case E_COMPILE_ERROR:
                case E_USER_ERROR:
                    $this->_error_log( $message );
                    $this->_show_error ('XXX','XXXX');
                    break;
                case E_WARNING:
                case E_NOTICE:
                    $this->_error_log( $message );
                    $this->_show_error (78,'警告级别错误');
                    break;
                case E_STRICT:
                case E_USER_WARNING:
                case E_USER_NOTICE:
                    break;
                default:
                    break;

            }

        }

        public function _exception_handler ( $e ) {
            $this->_show_error( $e->getCode(), $e->getMessage() );
            exit();
        }

        public function _shutdown_handler () {
            $error = error_get_last();
            if($error){
                $message = $error['message'].' '.$error['file'].' '.$error['line'];
                $this->_error_log($message);
                $this->_show_error(99,'意外关闭');
            }
            exit;

        }


        public function _error_log ( $errmsg ) {
            //通过异步任务 写入mongodb等
            file_put_contents( dirname(__FILE__). DIRECTORY_SEPARATOR .'error.log', $errmsg);
        }


        public function _show_error ($code=null,$message,$http_code=200) {
            $resp = new \stdClass();
            $resp->errno = $code;
            $resp->errmsg = $message;
            $this->__output($resp,$http_code);
        }

        public function __output ( $resp,$http_code,$output = '' ) {

            if(!$output){
                $output = $this->output_format;
            }

            switch ($output) {
                case 'json': 
                    $ctype = 'application/json';
                    break;
                case 'text': 
                    $ctype = 'text/plain';
                    break;
                case 'enctype': 
                    $ctype = 'application/x-www-form-urlencoded';
                    break;
                case 'xml': 
                    $ctype = 'application/xml';
                    break;
                default: 
                    $ctype = 'application/x-www-form-urlencoded';
                    break;
            }

            header('Content-type: ' . $ctype.'; charset=utf8');
            header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');
            header('Accept-Encoding: gzip, deflate');
            header('Pragma: no-cache');
            header('Connection: close');

            switch ( $output ) {
                case 'json':
                    echo json_encode($resp);
                    break;
                case 'xml':
                    echo  xml_encode($resp);
                    break;
                default:
                    echo $resp;
                    break;
            }
            exit();
        }
    }
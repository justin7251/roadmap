<?php
/**
* /core/controller.php
*
* PHP Version 5
*/

/**
* The Base Controller
*
* @category Controller
* @package Roadmap
* @subpackage Controller
* @author Justin Leung <myjustinleung19@hotmail.com>
* @copyright 2020
* @version Release: 1.0
*/
class Controller
{
    /**
    * @var null Database Connection
    */
    public $db = null;

    /**
    * @var string The name of the header view
    */
    public $header = 'header';
    
    /**
    * @var string The name of the footer view
    */
    public $footer = 'footer';
    
    /**
    * @var null Model
    */
    public $model = null;
    
    /**
    * @var
    */
    public $controller = null;
    
    /**
    * @var
    */
    protected $action_name = null;
    
    /**
    * @var
    */
    protected $error = null;
    
    /**
    * @var
    */
    protected $loggedin = array();
    
    /**
    * @var array All variables to be passed through to the view
    */
    private $view_vars = array();
    
    /**
    * @var
    */
    protected $projects;

    /**
    * @var
    */
    protected $current_project_id;
    
    /**
    * @var
    */
    protected $render = true;
    
    /**
    * Whenever controller is created, open a database connection too and load "the model".
    */
    public function __construct()
    {
        if (!$this->db) {
            $this->openDatabaseConnection();
        }
        $this->controller = get_class($this);
        $this->loadModel();
        $this->stripPost();
        
        if (!Session::get('all_projects')) {
            $Projects = new Project_Model($this->db);
            Session::set('all_projects', $Projects->get(array('id', 'name', 'description'), array('deleted' => 0)));
        } 
        $this->projects = Session::get('all_projects');
        $this->setViewVar('projects', $this->projects);
        if (!Session::get('current_project_id')) {
            Session::set('current_project_id', $this->projects[0]['id']);
        }
        if (!Session::get('current_project_name')) {
            Session::set('current_project_name', $this->projects[0]['name']);
        }
        if (!Session::get('current_project_description')) {
            Session::set('current_project_description', $this->projects[0]['description']);
        }
        $this->current_project_id = Session::get('current_project_id');
        $this->setViewVar('current_project_id', $this->current_project_id);
    }
    
    /**
     * "Start" the application:
     * Analyze the URL elements and calls the according controller/method or the fallback
     */
    public function __destruct()
    {
        if (strtolower($this->controller) != 'project' || strtolower($this->controller) != 'setting') {
            Session::set('last_view_page', strtolower($this->controller) . '/' . (isset($this->render_action) ? $this->render_action : $this->action_name));
        }
        if (!$this->render) {
            return;
        }
        extract($this->view_vars);
        
        require_once APP . 'view/_templates/' . $this->header . '.php';
        require_once APP . 'view/' . strtolower($this->controller) . '/' . (isset($this->render_action) ? $this->render_action : $this->action_name) . '.php';
        require_once APP . 'view/_templates/' . $this->footer . '.php';
    }
    
    /**
    *
    */
    public function setViewVar($name, $variable)
    {
        $this->view_vars[$name] = $variable;
    }
    
    /**
    *
    */
    private function stripPost()
    {
        if ($_POST) {
            foreach($_POST as $key => $value) {
                if (preg_match('/date/', $key)) {
                    $_POST[$key] = Helper::save_date_time($value);
                } else {
                    if (is_array($value)) {
                        foreach ($value as &$val) {
                            trim($val);
                        }
                        $_POST[$key] = $value;
                    } else {
                        $_POST[$key] = trim($value);
                    }
                }
            }
        }
    }
    
    /**
    *
    */
    public function checkLoggedIn()
    {
        if (in_array($this->action_name, $this->loggedin)) {
            Access::has_session();
        }
    }
    
    /**
    *
    */
    public function setActionName($name)
    {
        $this->action_name = $name;
    }

    /**
    * Limit text to a certain limit
    *
    * @return string
    */
    public function limit_text($text, $limit) {
      if (str_word_count($text, 0) > $limit) {
          $words = str_word_count($text, 2);
          $pos = array_keys($words);
          $text = substr($text, 0, $pos[$limit]) . '...';
      }
      return $text;
    }

    /**
    * Open the database connection with the credentials from application/config/config.php
    */
    private function openDatabaseConnection()
    {
        // set the (optional) options of the PDO connection. in this case, we set the fetch mode to
        // "objects", which means all results will be objects, like this: $result->user_name !
        // For example, fetch mode FETCH_ASSOC would return results like this: $result["user_name] !
        // @see http://www.php.net/manual/en/pdostatement.fetch.php
        $options = array(PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ, PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING);

        // generate a database connection, using the PDO connector
        // @see http://net.tutsplus.com/tutorials/php/why-you-should-be-using-phps-pdo-for-database-access/
        $this->db = new PDO(DB_TYPE . ':host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET, DB_USER, DB_PASS, $options);
    }

    /**
    * Loads the "model".
    * @return object model
    */
    public function loadModel()
    {
        $path = APP . 'model/' . strtolower($this->controller) . '_model.php';
        if (file_exists($path)) {
            require_once $path;
            $modelName = ucfirst($this->controller) . '_Model';
            $this->model = new $modelName($this->db);
        }
    }
    
    /**
    *
    */
    protected function checkError()
    {
        if ($this->error) {
            return $this->error;
        } else {
            return false;
        }
    }
}

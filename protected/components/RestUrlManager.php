<?php 
/**
 * Yii RESTful URL Manager
 *
 * Example : 
 *  copy this file to components dir
 *  
 *  1. config
 *    'urlManager'=>array(
 *         'class' => 'RestUrlManager',
 *         'urlFormat' => 'path',
 *         'showScriptName' => false,
 *         'resources' => array(
 *             'rest',
 *         ),
 *    ),
 *    
 *    -> 
 *    `GET` http://yourdomain/rest          => RestController::actionIndex
 *    `GET` http://yourdomain/rest/{id}     => RestController::actionShow
 *    `POST` http://yourdomain/rest         => RestController::actionCreate
 *    `PUT` http://yourdomain/rest/{id}     => RestController::actionUpdate
 *    `DELETE` http://yourdomain/rest/{id}  => RestController::actionDelete
 *
 *  2. Specified parameters 
 *    'resources' => arary(
 *        'rest',
 *        array('posts', 'only' => array('index', 'create')), // just bind Posts::index, Posts::create action
 *        array('users', 'except' => array('delete', 'update'), // except Posts::delete, Posts::update action
 *    ),
 *
 *  3. nginx conf
 *    
 *  if (!-f $request_filename){  
 *      rewrite (.*) /index.php;  
 *  }  
 *
 *  location ~ (/shua-php.*.css$) { }
 *  location ~ /shua-php.*$ {
 *      if (!-f $request_filename){
 *          rewrite (/shua-php.*) /shua-php/index.php last;
 *      }
 *      fastcgi_pass unix:/var/run/php5-fpm.sock;
 *      fastcgi_index index.php?IF_REWRITE=1;
 *      include fastcgi_params;
 *  }
 */
 
class RestUrlManager extends \CUrlManager
{
    public $resources;

    protected $actions = array('index', 'show', 'create', 'update', 'delete');

    protected $resourcePatterns = array(
        'index'  => array('<controller>/index', 'pattern' => '<controller:({resources})>', 'verb' => 'GET'),
        'show'   => array('<controller>/show', 'pattern' => '<controller:({resources})>/<id:\d+>', 'verb' => 'GET'),
        'create' => array('<controller>/create', 'pattern' => '<controller:({resources})>', 'verb' => 'POST'),
        'update' => array('<controller>/update', 'pattern' => '<controller:({resources})>/<id:\d+>', 'verb' => 'PUT'),
        'delete' => array('<controller>/delete', 'pattern' => '<controller:({resources})>/<id:\d+>', 'verb' => 'DELETE'),
    );

    protected $subResourcePatterns = array(
        'index'  => array('<controller>/index', 'pattern' => '<relation:({resources})>/<relation_id:\d+>/<controller:({subresources})>', 'verb' => 'GET'),
        'show'   => array('<controller>/show', 'pattern' => '<relation:({resources})>/<relation_id:\d+>/<controller:({subresources})>/<id:\d+>', 'verb' => 'GET'),
        'create' => array('<controller>/create', 'pattern' => '<relation:({resources})>/<relation_id:\d+>/<controller:({subresources})>', 'verb' => 'POST'),
        'update' => array('<controller>/update', 'pattern' => '<relation:({resources})>/<relation_id:\d+>/<controller:({subresources})>/<id:\d+>', 'verb' => 'PUT'),
        'delete' => array('<controller>/delete', 'pattern' => '<relation:({resources})>/<relation_id:\d+>/<controller:({subresources})>/<id:\d+>', 'verb' => 'DELETE'),
    );

    public function init()
    {
        $this->initRules();
        parent::init();
    }

    public function initRules()
    {
        $resourceActions = $subResourceActions = array();
        foreach ($this->resources as $options) {
            if (is_string($options)) {
                $options = array($options);
            }

            $actions = $this->parseActions($options);
            $subResource = false;
            if (strpos($options[0], '.') !== false) {
                list($resource, $subResource) = explode('.', $options[0]);
            } else {
                $resource = $options[0];
            }
            foreach ($actions as $action) {
                if ($subResource) {
                    $subResourceActions[$action][$resource] = $subResource;
                } else {
                    $resourceActions[$action][] = $options[0];
                }
            }
        }

        foreach ($resourceActions as $action => $resources) {
            $this->rules[] = str_replace('{resources}', implode('|', $resources), $this->resourcePatterns[$action]);
        }

        foreach ($subResourceActions as $action => $resources) {
            $this->rules[] = str_replace(
                array('{resources}', '{subresources}'), 
                array(implode('|', array_keys($resources)), implode('|', $resources)), 
                $this->subResourcePatterns[$action]);
        }
    }

    public function parseActions($options)
    {
        $actions = $this->actions;

        if (isset($options['only'])) {
            $actions = array_intersect($this->actions, $options['only']);
        }

        if (isset($options['except'])) {
            $actions = array_diff($this->actions, $options['except']);
        }

        return $actions;
    }
}

<?php

declare(strict_types=1);

namespace preloader;

include_once('ModelDatabase.php');

/**
 * Model log.
 * Important messages, notices and errors can be saved as logs.
 * 
 * @property string|null $id Log`s id.
 * @property string|null $siteId Site`s id.
 * @property string|null $requestId Request`s id.
 * @property int|null $level Log`s level.
 * @property string|null $message Log`s message.
 * @property string|null $description Log`s description.
 * @property array|null $data Log`s additional data.
 * @property int|null $code Php error level constant.
 * @property string|null $file File`s path.
 * @property string|null $line Line of the file.
 * @property string|null $url Url.
 */
class ModelLog extends ModelDatabase {
    /**
     * Log`s levels.
     */
    public const LEVEL_CRITICAL = 1;
    public const LEVEL_ERROR = 2;
    public const LEVEL_WARNING = 4;
    public const LEVEL_NOTICE = 8;
    
    protected $_id = null;
    protected $_siteId = null;
    protected $_requestId = null;
    protected $_level = null;
    protected $_message = null;
    protected $_description = null;
    protected $_data = null;
    protected $_code = null;
    protected $_file = null;
    protected $_line = null;
    protected $_url = null;
    
    /**
     * @var string $_table Name of database table.
     */
    protected $_table = 'log';
    
    /**
     * @var ModelSite $_modelSite Site model object.
     */
    protected $_modelSite = null;
    
    /**
     * @var ModelRequest $_modelRequest Request model object.
     */
    protected $_modelRequest = null;
    
    /**
     * Class constructor.
     */
    public function __construct() {
        parent::__construct();
        
        $this->addHiddenProperty('_modelSite');
        $this->addHiddenProperty('_modelRequest');
    }
    
    /**
     * Sets log information from params to this object.
     */
    public function create(
        int $level, string $message, string $description = null,
        array $data = null, int $code = null,
        string $file = null, int $line = null, string $url = null
    ): bool {
        $result = false;
        
        if ($this->getModelRequest()) {
            if (! $this->getModelRequest()->id) {
                $this->getModelRequest()->save();
            }
        }
        
        if ($this->checkLevel($level) && strlen($message)) {
            if ($this->getModelSite()) {
                $this->siteId = $this->getModelSite()->id;
            } else {
                $this->siteId = null;
            }
            
            if ($this->getModelRequest()) {
                $this->requestId = $this->getModelRequest()->id;
            } else {
                $this->requestId = null;
            }
            
            $this->level = $level;
            $this->message = $message;
            $this->description = $description;
            $this->data = $data;
            $this->code = $code;
            $this->file = $file;
            $this->line = $line;
            $this->url = $url;
            
            $result = true;
        }
        
        return $result;
    }
    
    /**
     * Creates critical error log.
     */
    public function createCritical(
        string $message, string $description = null,
        array $data = null, int $code = null,
        string $file = null, int $line = null, string $url = null
    ): bool {
        if ($code === null) {
            $code = E_USER_ERROR;
        }
        return $this->create(self::LEVEL_CRITICAL, $message, $description, $data, $code, $file, $line, $url);
    }
    
    /**
     * Creates error log.
     */
    public function createError(
        string $message, string $description = null,
        array $data = null, int $code = null,
        string $file = null, int $line = null, string $url = null
    ): bool {
        if ($code === null) {
            $code = E_USER_ERROR;
        }
        return $this->create(self::LEVEL_ERROR, $message, $description, $data, $code, $file, $line, $url);
    }
    
    /**
     * Creates warning log.
     */
    public function createWarning(
        string $message, string $description = null,
        array $data = null, int $code = null,
        string $file = null, int $line = null, string $url = null
    ): bool {
        if ($code === null) {
            $code = E_USER_WARNING;
        }
        return $this->create(self::LEVEL_WARNING, $message, $description, $data, $code, $file, $line, $url);
    }
    
    /**
     * Creates notice log.
     */
    public function createNotice(
        string $message, string $description = null,
        array $data = null, int $code = null,
        string $file = null, int $line = null, string $url = null
    ): bool {
       if ($code === null) {
            $code = E_USER_NOTICE ;
        }
        return $this->create(self::LEVEL_NOTICE, $message, $description, $data, $code, $file, $line, $url);
    }
    
    /**
     * Checks if log`s level is correct.
     */
    protected function checkLevel(int $level): bool {
        $result = false;
        
        if ($level) {
            if (
                $level === self::LEVEL_CRITICAL
                || $level === self::LEVEL_ERROR
                || $level === self::LEVEL_WARNING
                || $level === self::LEVEL_NOTICE
            ) {
                $result = true;
            }
        }
        
        return $result;
    }
    
    /**
     * Set log`s message property.
     */
    protected function setMessage(string $value): void {
        $maxMessageLength = 255;
        
        if ($value) {
            if (strlen($value) > $maxMessageLength) {
                $value = substr($value, 0, $maxMessageLength);
            }
        }
        
        $this->_message = $value;
    }
    
    /**
     * Set log`s url property.
     */
    protected function setUrl(string $value): void {
        $maxUrlLength = 255;
        
        if ($value) {
            if (strlen($value) > $maxUrlLength) {
                $value = substr($value, 0, $maxUrlLength);
            }
        }
        
        $this->_url = $value;
    }
    
    /**
     * Gets log`s data property.
     */
    public function getData() {
        return $this->_data ? json_decode($this->_data, true) : null;
    }
    
    /**
     * Sets log`s data property.
     */
    public function setData($value): void {
        $this->_data = json_encode($value);
    }
    
    /**
     * Gets log`s site model.
     */
    protected function getModelSite(): ModelSite {
        return $this->_modelSite;
    }
    
    /**
     * Sets log`s site model.
     */
    public function setModelSite(ModelSite $modelSite): bool {
        $result = false;
        
        if (is_object($modelSite) && $modelSite instanceof ModelSite) {
            $this->_modelSite = $modelSite;
            $result = true;
        }
        
        return $result;
    }
    
    /**
     * Gets log`s request model.
     */
    protected function getModelRequest(): ModelRequest {
        return $this->_modelRequest;
    }
    
    /**
     * Sets log`s request model.
     */
    public function setModelRequest(ModelRequest $modelRequest): bool {
        $result = false;
        
        if (is_object($modelRequest) && $modelRequest instanceof ModelRequest) {
            $this->_modelRequest = $modelRequest;
            $result = true;
        }
        
        return $result;
    }
    
}
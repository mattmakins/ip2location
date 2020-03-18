<?php
namespace Ip2Location;

use PDO;

class Ip2Location
{
    protected $databaseHost = 'localhost';
    
    protected $databaseName = 'ip2location';
    
    protected $databaseUser = 'ip2location';
    
    protected $databasePassword;    
    
    protected $tableName = 'ip2location_db1';
    
    protected $ipFromColumnName = 'ip_from';
    
    protected $ipToColumnName = 'ip_to';
    
    protected $countryCodeColumnName = 'country_code';
        
    /**
     * @param array $options
     */
    public function __construct($options = array())
    {
        $this->setOptions($options);
        
        if (!isset($options['db_pass'])) {
            
            throw new \InvalidArgumentException('db password must be set');
        }
        
        $this->databasePassword = $options['db_pass'];
    }
    
    public function getCountry($ip)
    {                
        return $this->query($this->dot2LongIp($ip));
    }
    
    private function dot2LongIp($ip)
    {
        if ($ip == "") {
            return 0;
        } else {
            $ips = explode(".", "$ip");
            return ($ips[3] + $ips[2] * 256 + $ips[1] * 256 * 256 + $ips[0] * 256 * 256 * 256);
        }        
    }
    
    private function query($longIp)
    {
        $conn = $this->getDbConnection();
        
        $statement = $conn->prepare("select {$this->countryCodeColumnName} from {$this->tableName}"
            . " where {$this->ipFromColumnName} <= {$longIp} and {$this->ipToColumnName}"
            . " >= {$longIp}");
        
        $statement->execute();
            
        $statement->setFetchMode(PDO::FETCH_ASSOC);
        
        $result = $statement->fetchAll();

        // close connection
        $conn = null;
        
        return $result[0]["{$this->countryCodeColumnName}"];        
    }
    
    /**
     * 
     * @return PDO
     */
    private function getDbConnection()
    {
        return new PDO("mysql:host={$this->databaseHost};dbname={$this->databaseName}",
            $this->databaseUser, $this->databasePassword);
    }
    
    private function setOptions($options = array())
    {
        if (isset($options['db_host'])) {
            
            $this->databaseHost = $options['db_host'];
        }
        
        if (isset($options['db_name'])) {
            
            $this->databaseName = $options['db_name'];
        }
        
        if (isset($options['db_user'])) {
            
            $this->databaseUser = $options['db_user'];
        }
        
        if (isset($options['table_name'])) {
            
            $this->tableName = $options['table_name'];
        }        
        
        if (isset($options['ip_from_column_name'])) {
            
            $this->ipFromColumnName = $options['ip_from_column_name'];
        }
        
        if (isset($options['ip_to_column_name'])) {
            
            $this->ipToColumnName = $options['ip_to_column_name'];
        }
        
        if (isset($options['country_code_column_name'])) {
            
            $this->countryCodeColumnName = $options['country_code_column_name'];
        }        
    }
}

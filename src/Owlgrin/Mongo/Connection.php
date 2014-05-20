<?php namespace Owlgrin\Mongo;

use MongoClient;
use Illuminate\Database\Connection as IlluminateConnection;

class Connection extends IlluminateConnection {

    /**
     * The MongoDB database handler.
     *
     * @var resource
     */
    protected $db;

    /**
     * The MongoClient connection handler.
     *
     * @var resource
     */
    protected $connection;

    /**
     * Create a new database connection instance.
     *
     * @param  array   $config
     * @return void
     */
    public function __construct(array $config)
    {
        $this->config = $config;

        // Build the connection string
        $dsn = $this->getDsn($config);

        // You can pass options directly to the MongoClient constructor
        $options = array_get($config, 'options', array());

        // Create the connection
        $this->connection = $this->createConnection($dsn, $config, $options);

        // Select database
        $this->db = $this->connection->{$config['database']};
    }

    /**
     * Helper method to get started with DB facade.
     * @param  string $name
     * @return MongoCollection
     */
    public function collection($name)
    {
        return $this->getCollection($name);
    }

    /**
     * Get a MongoDB collection.
     *
     * @param  string   $name
     * @return MongoCollection
     */
    public function getCollection($name)
    {
        return $this->db->{$name};
    }

    /**
     * Get the MongoDB database object.
     *
     * @return  MongoDB
     */
    public function getMongoDB()
    {
        return $this->db;
    }

    /**
     * return MongoClient object
     *
     * @return MongoClient
     */
    public function getMongoClient()
    {
        return $this->connection;
    }

    /**
     * Create a new MongoClient connection.
     *
     * @param  string  $dsn
     * @param  array   $config
     * @param  array   $options
     * @return MongoClient
     */
    protected function createConnection($dsn, array $config, array $options)
    {
        // Add credentials as options, this makes sure the connection will not fail if
        // the username or password contains strange characters.
        if (isset($config['username']) && $config['username'])
        {
            $options['username'] = $config['username'];
        }

        if (isset($config['password']) && $config['password'])
        {
            $options['password'] = $config['password'];
        }

        return new MongoClient($dsn, $options);
    }

    /**
     * Create a DSN string from a configuration.
     *
     * @param  array   $config
     * @return string
     */
    protected function getDsn(array $config)
    {
        // First we will create the basic DSN setup as well as the port if it is in
        // in the configuration options. This will give us the basic DSN we will
        // need to establish the MongoClient and return them back for use.
        extract($config);

        // Treat host option as array of hosts
        $hosts = is_array($config['host']) ? $config['host'] : array($config['host']);

        // Add ports to hosts
        foreach ($hosts as &$host)
        {
            if (isset($config['port']))
            {
                $host = "{$host}:{$port}";
            }
        }

        // The database name needs to be in the connection string, otherwise it will
        // authenticate to the admin database, which may result in permission errors.
        return "mongodb://" . implode(',', $hosts) . "/{$database}";
    }

    /**
     * Get the elapsed time since a given starting point.
     *
     * @param  int    $start
     * @return float
     */
    public function getElapsedTime($start)
    {
        return parent::getElapsedTime($start);
    }

    /**
    * Get the Mongo driver name.
    *
    * @return string
    */
    public function getDriverName()
    {
        return 'mongodb';
    }

    /**
     * Dynamically pass methods to the connection.
     *
     * @param  string  $method
     * @param  array   $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return call_user_func_array(array($this->db, $method), $parameters);
    }

    /**
     * Method to make it easy to work on collections
     * @param  string $name
     * @return MongoCollection
     */
    public function __get($name)
    {
        return $this->getCollection($name);
    }

}
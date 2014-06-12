<?php namespace Owlgrin\Mongo\Auth;

use MongoId;
use Illuminate\Database\Connection;
use Illuminate\Hashing\HasherInterface;
use Illuminate\Encryption\Encrypter;
use Illuminate\Auth\UserProviderInterface;
use Illuminate\Auth\UserInterface;

class MongoUserProvider implements UserProviderInterface {

	/**
	 * The active database connection.
	 *
	 * @var  \Illuminate\Database\Connection
	 */
	protected $conn;

	/**
	 * The hasher instance.
	 *
	 * @var  \Illuminate\Hashing\HashingInterface
	 */
	protected $hasher;

	/**
	 * The collection containing the users.
	 *
	 * @var string
	 */
	protected $collection;

	/**
	 * The encrypter instance.
	 *
	 * @var \Illuminate\Encryption\Encrypter
	 */
	
	protected $encrypter;

	/**
	 * Is the password encrypted?
	 *
	 * @var boolean
	 */
	
	protected $encryptedPassword;

	/**
	 * Create a new mongo user provider.
	 *
	 * @param  \Illuminate\Database\Connection  $conn
	 * @param  string  $collection
	 * @return void
	 */
	public function __construct(Connection $conn, HasherInterface $hasher, Encrypter $encrypter, $collection, $encryptedPassword)
	{
		$this->conn = $conn;
		$this->hasher = $hasher;
		$this->encrypter = $encrypter;
		$this->collection = $collection;
		$this->encryptedPassword = $encryptedPassword;
	}

	/**
	 * Retrieve a user by their unique identifier.
	 *
	 * @param  mixed  $identifier
	 * @return \Illuminate\Auth\UserInterface|null
	 */
	public function retrieveById($identifier)
	{
		$user = $this->conn->{$this->collection}->findOne(array('_id' => new MongoId($identifier)));

		if( ! is_null($user))
		{
			return new MongoUser($user);
		}
	}

	/**
	 * Retrieve a user by by their unique identifier and "remember me" token.
	 *
	 * @param  mixed  $identifier
	 * @param  string  $token
	 * @return \Illuminate\Auth\UserInterface|null
	 */
	public function retrieveByToken($identifier, $token)
	{
		// not implemented yet, because it is not required yet
	}

	/**
	 * Update the "remember me" token for the given user in storage.
	 *
	 * @param  \Illuminate\Auth\UserInterface  $user
	 * @param  string  $token
	 * @return void
	 */
	public function updateRememberToken(UserInterface $user, $token)
	{
		// not implemented yet, because it is not required yet
	}

	/**
	 * Retrieve a user by the given credentials.
	 *
	 * @param  array  $credentials
	 * @return \Illuminate\Auth\UserInterface|null
	 */
	public function retrieveByCredentials(array $credentials)
	{
		// First, we will make the array of the conditions required
		$conditions = array();
		foreach($credentials as $key => $value)
		{
			if( ! str_contains($key, 'password'))
			{
				$conditions[$key] = str_is($key, '_id') ? new MongoId($value) : $value;
			}
		}

		// Now we are ready to execute the query to see if we have an user matching
		// the given credentials. If not, we will just return nulls and indicate
		// that there are no matching users for these given credential arrays.
		$user = $this->conn->{$this->collection}->findOne($conditions);

		if( ! is_null($user))
		{
			return new MongoUser($user);
		}
	}

	/**
	 * Validate a user against the given credentials.
	 *
	 * @param  \Illuminate\Auth\UserInterface  $user
	 * @param  array  $credentials
	 * @return bool
	 */
	public function validateCredentials(UserInterface $user, array $credentials)
	{
		$plain = $credentials['password'];

		if($this->encryptedPassword)
		{
			return $plain == $this->encrypter->decrypt($user->getAuthPassword());
		}

		return $this->hasher->check($plain, $user->getAuthPassword());
	}
}
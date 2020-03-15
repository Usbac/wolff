<?php

namespace Utilities;

class Auth extends \Core\DB
{

    const DEFAULT_TABLE = 'user';

    /**
     * The password hashing options
     *
     * @var array
     */
    private static $options = [
        'cost' => '10',
    ];

    /**
     * The database table to be used.
     *
     * @var string
     */
    private static $table = self::DEFAULT_TABLE;

    /**
     * The last currently authenticated
     * user's data.
     *
     * @var array
     */
    private static $user = null;

    /**
     * The last inserted user id.
     *
     * @var int
     */
    private static $last_id = 0;


    /**
     * Set the database table to be used
     *
     * @param  string  $table  the database table
     */
    public static function setTable(string $table = self::DEFAULT_TABLE)
    {
        self::$table = parent::escape($table);
    }


    /**
     * Returns the database table to be used
     *
     * @return string the database table to be used
     */
    public static function getTable()
    {
        return self::$table;
    }


    /**
     * Set the password hash options
     *
     * @param  array  $options  the password hash options
     */
    public static function setOptions(array $options)
    {
        self::$options = $options;
    }


    /**
     * Returns the password hash options
     *
     * @return array the password hash options
     */
    public static function getOptions()
    {
        return self::$options;
    }


    /**
     * Returns the last inserted id
     *
     * @return int the last inserted id
     */
    public static function getId()
    {
        return self::$last_id;
    }


    /**
     * Returns the currently authenticated
     * user's data
     *
     * @return array the currently authenticated
     * user's data
     */
    public static function getUser()
    {
        return self::$user;
    }


    /**
     * Returns true if the given user data exists in the database
     * and is valid, false otherwise.
     * This method updates the current user data array.
     *
     * @param  array  $data  the array containing the user data
     *
     * @return bool true if the given user data exists in the database
     * and is valid, false otherwise
     */
    public static function login(array $data)
    {
        //Fields validation
        if (!array_key_exists('password', $data)) {
            return false;
        }

        $password = $data['password'];
        unset($data['password']);

        $values = [];
        foreach (array_keys($data) as $key) {
            $values[] = "$key = :$key";
        }

        $values = implode(' AND ', $values);
        $table = self::getTable();

        $user = parent::query("SELECT * from `$table` WHERE $values", $data)->first();

        if (!array_key_exists('password', $user)) {
            self::$user = null;
            return false;
        }

        if (password_verify($password, $user['password'])) {
            self::$user = $user;
            return true;
        }

        self::$user = null;
        return false;
    }


    /**
     * Register a new user into the database.
     * The only required values in the given array
     * are 'password' and 'password_confirm'
     *
     * @param  array  $data  the array containing the user data
     *
     * @return bool true if the registration has been successfully made,
     * false otherwise
     */
    public static function register(array $data)
    {
        //Fields validation
        if (!self::passwordMatches($data)) {
            return false;
        }

        unset($data['password_confirm']);
        $data['password'] = self::getPassword($data['password']);

        if (self::insert($data)) {
            self::$last_id = parent::getPdo()->lastInsertId();
            return true;
        }

        return false;
    }


    /**
     * Returns true if the 'password' and the 'password_confirm' values
     * of the given array are equal, false otherwise
     *
     * @param  array  $data  the array containing the 'password'
     * and 'confirm_password' values
     *
     * @return bool true if the 'password' and the 'password_confirm' values
     * of the given array are equal, false otherwise
     */
    private static function passwordMatches(array $data)
    {
        return (array_key_exists('password', $data) &&
                array_key_exists('password_confirm', $data) &&
                $data['password'] === $data['password_confirm']);
    }


    /**
     * Returns the hashed password with the current options
     * and using the BCRYPT algorithm
     *
     * @param  string  $password  the password to hash
     *
     * @return string the hashed password
     */
    private static function getPassword(string $password)
    {
        return password_hash($password, PASSWORD_BCRYPT, self::$options);
    }


    /**
     * Insert the given values into the database
     *
     * @param  array  $data  the array containing the data
     *
     * @return bool true if the insertion has been successfully made,
     * false otherwise
     */
    private static function insert($data)
    {
        $array_keys = array_keys($data);

        $values = [];
        foreach ($array_keys as $key) {
            $values[] = ":$key";
        }

        $keys = implode(', ', $array_keys);
        $values = implode(', ', $values);
        $table = self::getTable();

        $stmt = parent::getPdo()->prepare("INSERT INTO `$table` ($keys) VALUES ($values)");

        return $stmt->execute($data);
    }

}

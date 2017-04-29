<?php

namespace app\models;
use yii\caching\FileCache;
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    

    
public static function tableName()
{
	return 'users';
}

    /**
     * @inheritdoc
     */
	 public function rules()
    {
        return [
           
            [['id'], 'integer'],
            [['auth_token'], 'string', 'max' => 100],
        ];
    }

    public static function findIdentity($id)
    {
        return isset(self::$users[$id]) ? new static(self::$users[$id]) : null;
    }

    /**
    Получаем пользователя по token
     */
	 
    public static function findIdentityByAccessToken($token, $type = null)
    {
		//Сначала пытаемся получить его из кэша и если есть, то создаем модель на основе полученных данных
		if(($uid = self::getFromCache($token)) !== false)
		{
			
			
			return self::createNewModel($token, $uid);
		} else if(($user =  self::findOne(['auth_key'=>$token])) != null){ //Если нет, то пишем в кэш для последующего получения
       

		self::storeToCache($token, $user->id);
		}
		return  is_null($user) ? null : $user;
    }
	//Функция создания модели на основе данных из кэша
	public static function createNewModel($token, $uid)
	{
		$model = new self();
		$model->id = $uid;
		$model->auth_key = $token;
		return $model;
	}
	public static function storeToCache($token, $uid)
	{
		
		
		
	 	 $cache = new FileCache();
		 $cache->set($token, $uid);
		
	}
	public function getLast()
	{
			 $cache = new FileCache();
			 $last = $cache->get("last_".$this->id);
			 
			 if($last === false)
				 return time();
			 else
				return $last;
	}
	//Обновление времени последнего запроса
	public function updateTime()
	{
		 $cache = new FileCache();
		 $cache->set("last_".$this->id, time());
	
	}
 public static function getFromCache($key)
 {
	 $cache = new FileCache();
	 //$cache->flush();
	 $data = $cache->get($key);
	
	 return $data !== false ? $data : false;
 }
    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        foreach (self::$users as $user) {
            if (strcasecmp($user['username'], $username) === 0) {
                return new static($user);
            }
        }

        return null;
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === $password;
    }
}

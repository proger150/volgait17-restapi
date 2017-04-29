<?php

namespace app\controllers;
use yii;
use app\models\StorageItem;
use yii\filters\auth\QueryParamAuth;
class UsersController extends \yii\rest\Controller
{
	public static $save_err = ['code'=>1,'text'=>'Ошибка сохранения данных'];
	public static $save_success = ['code'=>0,'text'=>'Данные успешно сохранены'];
	public static $find_err = ['code'=>1,'text'=>'Ошибка получения данных данного ключа не существует'];
	
	public function behaviors()
{
	
	$behaviors = parent::behaviors();
	$behaviors['authenticator'] = [
        'class' => QueryParamAuth::className(),
		'tokenParam'=>'token'
       
    ];
	$behaviors[] = [
        'class' => \yii\filters\ContentNegotiator::className(),
    
        'formats' => [
            'application/json' => \yii\web\Response::FORMAT_JSON,
        ],
    ];
	return $behaviors;
}
/*Здесь мы обновляем время последнего запроса к серверу после каждого обращения к методу, 
но при  условии что этот метод не является last-query, иначе при обращении мы затрем те данные, которые хотим получить
*/
public function beforeAction($action)
{
	if(!parent::beforeAction($action))
	{
		return false;
	}
	
	if($action->id != "last-query")
	Yii::$app->user->identity->updateTime();
	
	
	return true;
}
//Запись данных по ключу
    public function actionSet($key,$val)
    {
      $model = new StorageItem();
	  $model->data_key = $key;
	  $model->data_value = $val;
	  if($model->save())
		  return self::$save_success;
	  else
		  return  self::$save_err;
    }
	//Получение данных по ключу
	public function actionGet($key)
	{
		$user =  Yii::$app->user->identity;
		$model = StorageItem::findOne(['data_key'=>$key,'uid'=>$user->id]);
		if(is_null($model))
			return self::$find_err;
		else
			return $model->toArray(['data_key','data_value']);
	}
	//Метод получения времени пследнего запроса
 public function actionLastQuery()
 {
	 $user = Yii::$app->user->identity;
	return ['time'=>$user->last];
 }
}

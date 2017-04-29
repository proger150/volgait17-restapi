<?php

namespace app\controllers;
use yii;
use yii\filters\auth\QueryParamAuth;
class UsersController extends \yii\rest\Controller
{
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
    public function actionIndex()
    {
      
    }
	//Метод получения времени пследнего запроса
 public function actionLastQuery()
 {
	 $user = Yii::$app->user->identity;
	return ['time'=>$user->last];
 }
}

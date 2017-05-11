<?php

namespace app\controllers;

use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use app\models\Usuario;

class AjaxController extends \yii\web\Controller
{
    /**
    * @inheritdoc
    */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'ajax' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionBuscar($q, $cond)
    {
        if ($cond == 'todos') {

            $model = Usuario::find()->orWhere(['ilike', 'nombre', $q])
                                      ->orWhere(['ilike', 'poblacion', $q])
                                      ->orWhere(['ilike', 'provincia', $q])->all();
        } else {
            $model = Usuario::find()->where(['ilike', $cond, $q])->all();
        }

        return $this->renderAjax('/site/_usuarios', [
            'model' => $model,
        ]);
    }
}

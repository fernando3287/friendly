<?php

namespace app\controllers;

use Yii;
use app\helpers\Mensaje;
use app\models\Usuario;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\helpers\Url;
use kartik\widgets\AlertBlock;

class SiteController extends Controller
{
    /**
    * @inheritdoc
    */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'index'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return !Yii::$app->user->isGuest;
                        }
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
    * @inheritdoc
    */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
    * Displays homepage.
    *
    * @return string
    */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
    * Login action.
    *
    * @return string
    */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        //  Yii::$app
        // var_dump(Yii::$app->session->getFlash('error'));die();
        echo Yii::$app->session->getFlash('error', 'kdsfdkdnfkfdngvd');
        Mensaje::exito('"guay"');
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
    * Logout action.
    *
    * @return string
    */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
    * Displays contact page.
    *
    * @return string
    */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
    * Displays about page.
    *
    * @return string
    */
    public function actionAbout()
    {
        return $this->render('about');
    }


    public function actionCorreo()
    {
        $model = Usuario::findOne(1);
        Yii::$app->mailer->compose('usuarios/view', ['model' => $model])
        ->setFrom(Yii::$app->params['smtpUsername'])
        ->setTo('ricardo@iesdonana.org')
        ->setSubject('Prueba')
        //            ->setTextBody('Prueba')
        //            ->setHtmlBody('<b>Prueba</b>')
        ->send();
    }
}

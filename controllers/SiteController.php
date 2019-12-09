<?php

namespace app\controllers;

use app\models\db\Document;
use app\models\IinForm;
use app\models\ResultForm;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
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
     * {@inheritdoc}
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
     * Главная страница с формой для иин
     * @return string|\yii\console\Response|Response
     */
    public function actionIndex()
    {
        $model = new IinForm();
        $array_values = '';
        if ($model->load(Yii::$app->request->post()) && $array_values = $model->save()) {
            $session = Yii::$app->session;
            $session->set('array_values', $array_values);
            return Yii::$app->response->redirect(['site/result']);
        }
        if($array_values === false){
            Yii::$app->session->setFlash('FormSubmitted');
        }
        return $this->render('index', [
            'model' => $model,
        ]);
    }

    /**
     * Страница результата парсинга с возможностью сохранить данные
     * @return string|\yii\console\Response|Response
     */
    public function actionResult()
    {
        $session = Yii::$app->session;

        if ($session->has('array_values')) {
            $array_values = $session->get('array_values');
            $model = new ResultForm($array_values);
        } else {
            Yii::$app->session->setFlash('FormSubmitted');
            return Yii::$app->response->redirect(['site/index']);
        }
        if (Yii::$app->request->post()) {
            if ($model->load(Yii::$app->request->post()) && $model->create()) {
                return Yii::$app->response->redirect(['site/view']);
            }
        }
        return $this->render('result', [
            'model' => $model,
        ]);
    }

    /**
     * Вывод всех сохраненных документов
     * @return string
     */
    public function actionView()
    {
        $models = Document::find()->all();
        return $this->render('view', [
            'models' => $models,
        ]);
    }

    public function actionUpdate($id)
    {
        $document = Document::findOne($id);
        $model = new ResultForm($document);
        if (Yii::$app->request->post()) {
            if ($model->load(Yii::$app->request->post()) && $model->create()) {
                Yii::$app->session->setFlash('Update');
            }
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
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
}

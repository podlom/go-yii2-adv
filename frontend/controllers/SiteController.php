<?php

namespace frontend\controllers;

use yii\web\ErrorAction;
use yii\captcha\CaptchaAction;
use Throwable;
use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\BannerClickLog;
use common\models\LoginForm;
use common\models\TinyUrl;
use common\models\UrlRedirectLog;
use frontend\models\ContactForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResendVerificationEmailForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\VerifyEmailForm;

/**
 * Site controller
 */
class SiteController extends Controller
{
    public function beforeAction($action)
    {
        if ($action->id === 'log-banner-click') {
            Yii::$app->request->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
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
                'class' => ErrorAction::class,
            ],
            'captcha' => [
                'class' => CaptchaAction::class,
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Displays to page.
     *
     * @param string $url
     */
    public function actionTo($url): string
    {
        $request = Yii::$app->request;
        $ip = $request->userIP ?? 'unknown';
        $userAgent = $request->userAgent ?? 'unknown';

        Yii::info(__METHOD__ . ' +' . __LINE__ . ' $url: ' . var_export($url, true));
        Yii::info(__METHOD__ . ' +' . __LINE__ . ' $ip: ' . var_export($ip, true));
        Yii::info(__METHOD__ . ' +' . __LINE__ . ' browser: ' . var_export($userAgent, true));

        $redirectToUrl = null;

        if (is_numeric($url)) {
            $tinyUrl = TinyUrl::findOne(intval($url));
            Yii::info(__METHOD__ . ' +' . __LINE__ . ' $tinyUrl (by ID): ' . var_export($tinyUrl, true));
            if (!empty($tinyUrl)) {
                $redirectToUrl = $tinyUrl->url;
            }
        }

        if ($redirectToUrl === null) {
            $tinyUrl = TinyUrl::find()->where(['key' => $url])->one();
            Yii::info(__METHOD__ . ' +' . __LINE__ . ' $tinyUrl (by key): ' . var_export($tinyUrl, true));
            if (!empty($tinyUrl)) {
                $redirectToUrl = $tinyUrl->url;
            }
        }

        $redirectToUrl = null;

        if (is_numeric($url)) {
            $tinyUrl = TinyUrl::findOne(intval($url));
            Yii::info(__METHOD__ . ' +' . __LINE__ . ' $tinyUrl (by ID): ' . var_export($tinyUrl, true));
            if (!empty($tinyUrl)) {
                $redirectToUrl = $tinyUrl->url;
            }
        }

        if ($redirectToUrl === null) {
            $tinyUrl = TinyUrl::find()->where(['key' => $url])->one();
            Yii::info(__METHOD__ . ' +' . __LINE__ . ' $tinyUrl (by key): ' . var_export($tinyUrl, true));
            if (!empty($tinyUrl)) {
                $redirectToUrl = $tinyUrl->url;
            }
        }

        if ($redirectToUrl === null) {
            $decodedUrl = base64_decode($url);
            Yii::info(__METHOD__ . ' +' . __LINE__ . ' $decodedUrl: ' . var_export($decodedUrl, true));
            $redirectToUrl = $decodedUrl !== false ? $decodedUrl : $url;
        }

        Yii::info(__METHOD__ . ' +' . __LINE__ . ' $redirectToUrl: ' . var_export($redirectToUrl, true));

        $redirectTime = Yii::$app->params['redirect.time'] ?? 5;

        $s = $request->get('s');
        if (isset($s) && is_numeric($s) && intval($s) == $s) {
            $redirectTime = intval($s);
        }

        // Отримати геодані через Symfony-сервіс
        $geoInfo = [];
        try {
            $allowedKey = Yii::$app->params['bannerClick.key'] ?? null;
            $geoInfo = json_decode(file_get_contents("https://ip.shkodenko.com/ip-info?ipAddress={$ip}&key={$allowedKey}"), true);
        } catch (\Throwable $e) {
            Yii::warning('Failed to fetch geo info: ' . $e->getMessage());
        }
        Yii::info(__METHOD__ . ' +' . __LINE__ . ' $geoInfo: ' . var_export($geoInfo, true));

        $log = new UrlRedirectLog();
        $log->url = $redirectToUrl;
        $log->ip = $ip;
        $log->country = $geoInfo['country'] ?? null;
        $log->city = $geoInfo['city'] ?? null;
        $log->isp = $geoInfo['isp'] ?? null;
        $log->user_agent = $userAgent;
        $log->created_at = date('Y-m-d H:i:s');
        $isLogSaved = $log->save();

        if (!$isLogSaved) {
            Yii::error('UrlRedirectLog save error: ' . var_export($log->getErrors(), true));
        }

        return $this->render('to', [
            'url' => $redirectToUrl,
            'seconds' => $redirectTime,
        ]);
    }

    /**
     * Logs in a user.
     *
     * @return mixed
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
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        }

        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session->setFlash('success', 'Thank you for registration. Please check your inbox for verification email.');
            return $this->goHome();
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            }

            Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * Verify email address
     *
     * @param string $token
     * @return Response
     * @throws BadRequestHttpException
     */
    public function actionVerifyEmail($token)
    {
        try {
            $model = new VerifyEmailForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if (($user = $model->verifyEmail()) && Yii::$app->user->login($user)) {
            Yii::$app->session->setFlash('success', 'Your email has been confirmed!');
            return $this->goHome();
        }

        Yii::$app->session->setFlash('error', 'Sorry, we are unable to verify your account with provided token.');
        return $this->goHome();
    }

    /**
     * Resend verification email
     *
     * @return mixed
     */
    public function actionResendVerificationEmail()
    {
        $model = new ResendVerificationEmailForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                return $this->goHome();
            }
            Yii::$app->session->setFlash('error', 'Sorry, we are unable to resend verification email for the provided email address.');
        }

        return $this->render('resendVerificationEmail', [
            'model' => $model
        ]);
    }

    public function actionLogBannerClick(): Response
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $request = Yii::$app->request;

        $allowedKey = Yii::$app->params['bannerClick.key'] ?? null;

        $key = $request->post('key');

        if (empty($allowedKey) || $key != $allowedKey) {
            return $this->asJson([
                'error' => 1,
                'message' => 'Invalid or missing key',
            ]);
        }

        $ip = $request->post('ip');

        // Отримати геодані через Symfony-сервіс
        $geoInfo = [];
        try {
            $geoInfo = json_decode(file_get_contents("https://ip.shkodenko.com/ip-info?ipAddress={$ip}&key={$allowedKey}"), true);
        } catch (Throwable $e) {
            Yii::warning('Failed to fetch geo info: ' . $e->getMessage());
        }
        Yii::info(__METHOD__ . ' +' . __LINE__ . ' $geoInfo: ' . var_export($geoInfo, true));

        $model = new BannerClickLog([
            'url' => $request->post('url'),
            'ip' => $ip,
            'country' => $geoInfo['country'] ?? null,
            'city' => $geoInfo['city'] ?? null,
            'isp' => $geoInfo['isp'] ?? null,
            'user_agent' => $request->post('user_agent'),
            'network' => $request->post('network'),
            'lang' => $request->post('lang', 'en'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        if ($model->save()) {
            return $this->asJson(['success' => true]);
        }

        return $this->asJson([
            'error' => 2,
            'message' => 'Validation failed',
            'details' => $model->getErrors(),
        ]);
    }
}

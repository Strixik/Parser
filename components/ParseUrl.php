<?php

namespace app\components;

use Yii;
use Sabirov\AntiCaptcha\ImageToText;

class ParseUrl extends \yii\base\BaseObject
{
    public function __construct($config = [])
    {
        Yii::setAlias('@frontend', dirname(__DIR__) . '/tmp');
        parent::__construct($config);
    }

    private $do, $iinBin, $code, $checksum, $first_img, $second_img;

    /**
     * Формируем строку для поста
     * @return string
     */
    protected function createPostRequestForCurl()
    {
        return "do=".$this->do."&number=" . $this->iinBin . "&code=" . $this->code . "&checksum=" . $this->checksum;
    }

    /**
     * Кулом забераем и обрабатываем данние
     * @param string $url
     * @param string $iin
     * @return array|bool
     */
    public function loadUsingCurl($url, $iin)
    {

        $this->iinBin = $iin;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url); // отправляем на
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:23.0) Gecko/20100101 Firefox/23.0");
        curl_setopt($ch, CURLOPT_HEADER, 0); // пустые заголовки
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // возвратить то что вернул сервер
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0); // следовать за редиректами
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);// таймаут4
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);// просто отключаем проверку сертификата
        curl_setopt($ch, CURLOPT_COOKIEJAR, Yii::getAlias('@frontend') . '/cookie.txt'); // сохранять куки в файл
        curl_setopt($ch, CURLOPT_COOKIEFILE, Yii::getAlias('@frontend'). '/cookie.txt');
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        $content = curl_exec($ch); //получаем главную страницу
        $this->setValues($content); //
        curl_setopt($ch, CURLOPT_URL, 'http://serj.ws' . $this->first_img);
        $content = curl_exec($ch);
        $img1 = $this->saveImage($content, 'logo1.png');
        curl_setopt($ch, CURLOPT_URL, 'http://serj.ws' . $this->second_img);
        $content = curl_exec($ch);
        $img2 = $this->saveImage($content, 'logo2.png');
        $this->montageImage($img1, $img2, 'out.png');
        $this->captchaCode();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->createPostRequestForCurl());
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec($ch);
        curl_close($ch);
        return $this->resultParse($server_output);
    }

    /**
     * Парсим сылки картинок, и полей для поста
     * @param string $content
     */
    protected function setValues($content)
    {
        preg_match_all('/<img[^>]*?src\s*=\s*[""\']?([^\'"" >]+?)[ \'""][^>]*?>/', $content, $matches);
        $this->first_img = str_replace('amp;', '', $matches[1][31]);
        $this->second_img = str_replace('amp;', '', $matches[1][32]);

        preg_match_all('/<input type="hidden"  name="checksum" value="(.*)"/', $content, $matches);
        $this->checksum = $matches[1][0];

        preg_match_all('/<input type="hidden" name="do" value="(.*)"/', $content, $matches);
        $this->do = $matches[1][0];
    }

    /**
     * Сохраняем картинки
     * @param string $content
     * @param string $name
     * @return string
     */
    protected function saveImage($content, $name)
    {
        $img = Yii::getAlias('@frontend'). '/' . $name;
        file_put_contents($img, $content);
        return $img;
    }

    /**
     * Соединяем 2 картинки в одну для распознавание
     * @param string $img1
     * @param string $img2
     * @param string $name
     */
    protected function montageImage($img1, $img2, $name)
    {
        exec('montage -geometry +0 -tile 2 ' . $img1 . ' ' . $img2 . ' ' . Yii::getAlias('@frontend') . '/' . $name);
    }

    /**
     * Распазнаем картинку
     * @return bool
     */
    protected function captchaCode()
    {
        $anticaptcha = new ImageToText();
        $anticaptcha->setVerboseMode(true); // chatty mode ON
        $anticaptcha->setKey(Yii::$app->params['key_anti_captcha']);
        $anticaptcha->setFile(Yii::getAlias('@frontend') . '/out.png');

        if (!$anticaptcha->createTask()) {
            $anticaptcha->debout("API v2 send failed - " . $anticaptcha->getErrorMessage(), "red");

            return false;
        }

        $taskId = $anticaptcha->getTaskId();

        if (!$anticaptcha->waitForResult()) {
            $anticaptcha->debout("could not solve captcha", "red");
            $anticaptcha->debout($anticaptcha->getErrorMessage());
        } else {
            $this->code = $anticaptcha->getTaskResult();
        }
    }

    /**
     * Парсим результат и формируем масив для работы
     * @param string $content
     * @return array|bool
     */
    protected function resultParse($content){
        preg_match_all('/<div class="otvetkt">((.*<br>\n){1,20})/', $content, $matches);
        if(isset($matches[0][0])){
            $div = $matches[0][0];
            preg_match_all("/<font .*'>(.*)<\/font>/", $div, $match);
            $documents = [];
            $i = 7;
            while (isset($match[1][$i])){
                $documents[] = $match[1][$i];
                ++$i;
            }

            $result_model = [
                'iin' => $match[1][0],
                'surname' => $match[1][1],
                'name' => $match[1][2],
                'patronymic' => $match[1][3],
                'date_of_birth' => $match[1][4],
                'nationality' => $match[1][5],
                'registration_address' => $match[1][6],
                'doc_numbers' => implode(', ', $documents),
            ];
            return $result_model;
        }
     return false;
    }


}
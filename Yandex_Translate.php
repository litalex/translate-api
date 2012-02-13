<?php
/*
 * ����� ��� ������������� API ����������� �� ������
 * ������� ��� ���������� ������, � ��������� ������� <-> ����������
 */
class Yandex_Translate {
    protected $rootURL = 'http://translate.yandex.ru/tr.json';
    protected $translatePath = '/translate';
    protected $langCodesPairsListPath = '/getLangs';

    /**
     * @var string - ������ ��� ��� ����� ������
     */
    public $eolSymbol = '<br />';

    /**
     * @var string - ����������� ������ � �������. ���� ���������� ��� ���������� ��������
     */
    public $langDelimiter = '-';

    protected $cURLHeaders = array(
            'User-Agent' => "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322; Media Center PC 4.0; .NET CLR 2.0.50727)",
            'Accept' => "text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
            'Accept-Language' => "ru,en-us;q=0.7,en;q=0.3",
            'Accept-Encoding' => "gzip,deflate",
            'Accept-Charset' => "windows-1251,utf-8;q=0.7,*;q=0.7",
            'Keep-Alive' => '300',
            'Connection' => 'keep-alive',
        );

    protected function yandexConnect($path, $transferData = array()) {
        $res = curl_init();
        $url = $this->rootURL.$path.'?'.http_build_query($transferData);
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_HTTPHEADER => $this->cURLHeaders,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 30,

        );
        curl_setopt_array($res, $options);
        $response = curl_exec($res);
        curl_close($res);

        return $response;
    }

    /**
     * @return mixed �������� ���� �������� from-to � ���� 'ru-uk', 'en-fr'
     */
    public function yandexGetLangsPairs(){

        $jsonLangsPairs = $this->yandexConnect($this->langCodesPairsListPath);

        return json_decode($jsonLangsPairs);
    }

    /**
     * @param array $langPairs
     * @return �������� �� ��� ��� ����� FROM
     */
    public function yandexGet_FROM_Langs(array $langPairs){
        foreach ($langPairs as $langPair){
            $smallArray = explode($this->langDelimiter, $langPair);
            $outerArray[$smallArray[0]] = $smallArray[0];
        }
        return $outerArray;
    }

    /**
     * @param array $langPairs
     * @return �������� �� ��� ��� ����� TO
     */
    public function yandexGet_TO_Langs(array $langPairs){
        foreach ($langPairs as $langPair){
            $smallArray = explode($this->langDelimiter, $langPair);
            $outerArray[$smallArray[1]] = $smallArray[1];
        }
        return $outerArray;
    }

    /**
     * ���������� �������
     * @param  $fromLang - � ������, ��� �����, 'ru' ����.
     * @param  $toLang - �� �����, ��� �����. �������: �� ��� ����� FROM �������� � TO
     * @param  $text - ����������� �����
     * @return mixed - �������. ������� �� ������������� eolSymbol
     */
    public function yandexTranslate($fromLang, $toLang, $text) {

        $transferData = array(
            'lang' => $fromLang.'-'.$toLang,
            'text' => $text,
        );

        $rawTranslate = $this->yandexConnect($this->translatePath, $transferData);

        $translate = str_replace('\n', $this->eolSymbol, $rawTranslate);

        return $translate;

    }
}

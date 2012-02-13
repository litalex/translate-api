<?php
class Big_Text_Translate {
    /**
     * @var int - ������������ ����� �������� ��� �������� �����������
     */
    public $symbolLimit = 2000;

    /**
     * @var int - ����������� ����� ��������, ����������� �� ����� �� ��������
     */
    public $totalSymbolLimit = 60000;

    /**
     * @var - �������, �� ������� ����� ������� �� �����������
     */
    public $sentensesDelimiter = '.';

    protected function toSentenses ($text) {
        $sentArray = explode($this->sentensesDelimiter, $text);
        return $sentArray;
    }

    /**
     * ���������� ������ �� ������ ������� ������
     * @param  $text
     * @return
     */

    public function toBigPieces ($text) {
        $sentArray = $this->toSentenses($text);
        $i = 0;
        $bigPiecesArray[0] = '';
        for ($k = 0; $k < count($sentArray); $k++) {
            $bigPiecesArray[$i] .= $sentArray[$k].$this->sentensesDelimiter;
            if (strlen($bigPiecesArray[$i]) > $this->symbolLimit){
                $i++;
                $bigPiecesArray[$i] = '';
            }
        }

        return $bigPiecesArray;
    }

    /**
     * ���������� ������. ����������, ����� �������� ��� ���� �-� (��. �������)
     * @param array $bigPiecesArray
     * @return string
     */
    public function fromBigPieces (array $bigPiecesArray) {
        return implode($bigPiecesArray);
    }

    /**
     * �������� ������ �� ���������� ������������� �������, ��� ���������� - false
     * @param  $text
     * @return bool
     */
    public function symbolCountControl ($text){
        return true;
        if (strlen($text) > $this->totalSymbolLimit){
            return false;
        }
    }

}
 

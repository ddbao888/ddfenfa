<?php


namespace App\Http\Controllers\Api\Transformer\Zds;

use App\Model\Zds\QuestionItem;
use League\Fractal\TransformerAbstract;

class QuestionItemTransformer extends TransformerAbstract
{
    public function transform(QuestionItem $item)
    {
        return [
            'uuid' => $item->uuid,
            'type' => $item->type,
            'title' => $item->title ? $item->title : $item->question->question_title,
            'answers' => isset($item->answer_items) ? explode('|', $item->answer_items) : $this->getChar(8, $item->answer),
            'answer_type' => $item->answer_items ? 1 : 2,//答题方式，1为选择，2随机字符
            'url' => $item->url,
            'time' => $item->question->answer_time,
        ];
    }
    function getChar($num,$str){
        $b = [];
        $answers = str_split($str,3);
        foreach($answers as $item){
            $b[] = ['label' => $item, 'is_select' => false, 'index' => 0];
        }
        for ($i=0; $i<$num; $i++) {
            // 使用chr()函数拼接双字节汉字，前一个chr()为高位字节，后一个为低位字节
            $a = chr(mt_rand(0xB0,0xD0)).chr(mt_rand(0xA1, 0xF0));
            // 转码
            $b[] = ['label' =>iconv('GB2312', 'UTF-8', $a), 'is_select' => false, 'index' => 0];
        }
        //$c = array_merge($b, $answers);
        shuffle($b);
        return $b;
    }
}

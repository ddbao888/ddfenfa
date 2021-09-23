<?php


namespace App\Http\Controllers\Api\Transformer\Zds;


use App\Model\Zds\ZdsUsers;
use League\Fractal\TransformerAbstract;

class ZdsUsersTransformer extends TransformerAbstract
{
    public function transform(ZdsUsers $user)
    {
        return [
            'real_name' => $user->real_name,
            'class_name' => $user->xq .'-'.$user->class_name,
            'giveaway' => $user->money,
            'num' => ($user->money/10).'盒',
        ];
    }
}
<?php
namespace App\Service\Compiler\Emitter\Types;


use App\Bytecode\Helper;
use App\Service\Compiler\FunctionMap\Manhunt2;

class T_HEADER_LEVEL_VAR_BOOLEAN {

    static public function map( $node, \Closure $getLine, \Closure $emitter, $data ){


        if ($data['calculateLineNumber']){
            $mapped = Manhunt2::$levelVarBoolean[ $node['value'] ];
        }else{
            $mapped = [
                'offset' => '12345678',
                'length' => 11
            ];
        }

        return [
            $getLine('1b000000'),
            $getLine($mapped['offset']),
        ];
    }

}
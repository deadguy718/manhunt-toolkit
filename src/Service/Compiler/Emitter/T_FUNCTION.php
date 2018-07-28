<?php
namespace App\Service\Compiler\Emitter;


use App\Bytecode\Helper;
use App\Service\Compiler\Evaluate;
use App\Service\Compiler\FunctionMap\Manhunt2;
use App\Service\Compiler\Token;

class T_FUNCTION {

    static public function map( $node, \Closure $getLine, \Closure $emitter, $data ){


        $code = [ ];

        if (isset($node['params']) && count($node['params'])){
            $skipNext = false;

            foreach ($node['params'] as $index => $param) {

                if ($skipNext){
                    $skipNext = false;
                    continue;
                }

                /**
                 * Define for INT, FLOAT and STRING a construct and destruct sequence
                 */
                if (
                    $param['type'] == Token::T_INT ||
                    $param['type'] == Token::T_FLOAT ||
                    $param['type'] == Token::T_TRUE ||
                    $param['type'] == Token::T_FALSE ||
                    $param['type'] == Token::T_SELF
                ) {

                    Evaluate::initializeParameterInteger($code, $getLine);

                    $resultCode = $emitter( $param );

                    foreach ($resultCode as $line) {
                        $code[] = $line;
                    }

                    Evaluate::returnResult($code, $getLine);

                }else if ($param['type'] == Token::T_STRING){

                    Evaluate::initializeReadHeaderString($code, $getLine);
                    Evaluate::processString($param, $code, $getLine, $data);
                    Evaluate::initializeParameterString($code, $getLine);

                    $resultCode = $emitter( $param );
                    foreach ($resultCode as $line) {
                        $code[] = $line;
                    }

                    Evaluate::returnResult($code, $getLine);
                    Evaluate::returnStringResult($code, $getLine);

                }else if ($param['type'] == Token::T_VARIABLE){

                    if (isset(Manhunt2::$functions[ strtolower($param['value']) ])) {
                        // mismatch, some function has no params and looks loke variables
                        // just redirect to the function handler

                        $result = $emitter( [
                            'type' => Token::T_FUNCTION,
                            'value' => $param['value']
                        ] );

                        foreach ($result as $item) {
                            $code[] = $item;
                        }

                        Evaluate::returnResult($code, $getLine);

                    }else{

                        if (isset(Manhunt2::$constants[ $param['value'] ])) {
                            $mapped = Manhunt2::$constants[$param['value']];
                            $mapped['section'] = "constant";

                        }else if (isset(Manhunt2::$levelVarBoolean[ $param['value'] ])) {
                            $mapped = Manhunt2::$levelVarBoolean[$param['value']];

                        }else if (isset($data['variables'][$param['value']])){
                            $mapped = $data['variables'][$param['value']];

                        }else if (isset($data['const'][$param['value']])){
                            $mapped = $data['const'][$param['value']];
                            $mapped['section'] = "script constant";
                        }else{



                            // we have a object notation here
                            if (strpos($param['value'], '.') !== false){
                                list($originalObject, $attribute) = explode('.', $param['value']);
                                $originalMap = $data['variables'][$originalObject];

                                if ($originalMap['type'] == "vec3d"){

                                    $mapped = [
                                        'section' => $originalMap['section'],
                                        'type' => 'object',
                                        'object' => $originalMap,
                                        'size' => 4
                                    ];

                                    switch ($attribute){
                                        case 'x':
                                            break;
                                        case 'y':
                                            $mapped['offset'] = '04000000';
                                            break;
                                        case 'z':
                                            $mapped['offset'] = '08000000';
                                            break;
                                    }

                                }else{
                                    throw new \Exception(sprintf("T_CONDITION: T_FUNCTION => unknown object type %s", $originalMap['type']));
                                }
                            }else{
                                $tmp = $getLine('dummy');
                                throw new \Exception(sprintf("T_FUNCTION: unable to find variable offset for %s (Line %s)", $param['value'], $tmp->lineNumber));

                            }
                        }

                        // initialize string
                        if ($mapped['section'] == "script") {
                            Evaluate::initializeReadScriptString($code, $getLine);
                        }else if ($mapped['section'] == "constant"){
                            Evaluate::initializeParameterInteger($code, $getLine);

                            //todo: hmm missmatch irgendwo...
                        }else if ($mapped['section'] == "script constant"){
                            Evaluate::initializeReadHeaderString($code, $getLine);
                        }else if (
                            $mapped['section'] == "header"
                        ){
                            Evaluate::initializeReadHeaderString($code, $getLine);
                        }else{
                                var_dump($mapped);
                            throw new \Exception(sprintf('Unknown section %s', $mapped['section']));
                        }

                        // define the offset

                        $code[] = $getLine($mapped['offset']);

                        if ($mapped['section'] == "script constant"){

                            Evaluate::initializeParameterString($code, $getLine);

                            $code[] = $getLine(Helper::fromIntToHex( $mapped['length'] + 1));

                            Evaluate::returnResult($code, $getLine);
                            Evaluate::returnStringResult($code, $getLine);

                        }else if ($mapped['section'] == "constant"){
                            Evaluate::returnResult($code, $getLine);

                        }else if ($mapped['section'] == "script"){
                            Evaluate::returnResult($code, $getLine);

                        }else if (
                            $mapped['section'] == "header" &&
                            $mapped['type'] == "stringArray"
                        ){
                            Evaluate::initializeParameterString($code, $getLine);

                            $code[] = $getLine(Helper::fromIntToHex( $mapped['size']));

                            Evaluate::returnResult($code, $getLine);
                            Evaluate::returnStringResult($code, $getLine);

                        }else if (
                            $mapped['section'] == "header" &&
                            $mapped['type'] == "Vec3D"
                        ){

                            Evaluate::returnResult($code, $getLine);

                        }else{
                                var_dump($mapped);
                            throw new \Exception(sprintf('Unknown section %s', $mapped['section']));

                        }

                    }


                }else if ($param['type'] == Token::T_ADDITION){
                    $result = T_ASSIGN::handleSimpleMath([
                        false,
                        $param,
                        $node['params'][$index + 1]
                    ], $getLine, $emitter, $data);

                    foreach ($result as $item) {
                        $code[] = $item;
                    }

                    Evaluate::returnResult($code, $getLine);

                    $skipNext = true;


                }else if ($param['type'] == Token::T_FUNCTION){
                    $resultCode = $emitter( $param );

                    foreach ($resultCode as $line) {
                        $code[] = $line;
                    }

                }else{
                    throw new \Exception(sprintf('Unknown type %s', $param['type']));
                }


                /**
                 * When the input value is a negative float or int
                 * we assign the positive value and negate them with this sequence
                 */
                if (
                    ($param['type'] == Token::T_INT || $param['type'] == Token::T_FLOAT) &&
                    $param['value'] < 0
                ) {

                    Evaluate::negateLastValue($code, $getLine);
                }

            }
        }

        /**
         * Translate function call
         */
        if (!isset(Manhunt2::$functions[ strtolower($node['value']) ])){
            throw new \Exception(sprintf('Unknown function %s', $node['value']));
        }


        $code[] = $getLine( Manhunt2::$functions[ strtolower($node['value']) ]['offset'] );

        // the setpedorientation call has a secret additional call
        if (
            strtolower($node['value']) == 'setpedorientation'
        ){

            Evaluate::returnResult($code, $getLine);

            $code[] = $getLine('b0020000');

        }

        // the writedebug call has a secret additional call, maybe a flush command ?
        if (
            strtolower($node['value']) == 'writedebug' //&&
        ){

            if (!isset($node['last']) || $node['last'] === true) {
                $code[] = $getLine('74000000');
            }
        }

        /**
         * when we are inside a nested call, tell the interpreter to return the current value
         */

        if (isset($node['nested']) && $node['nested'] === true){
            Evaluate::returnResult($code, $getLine);
        }

        return $code;
    }

}
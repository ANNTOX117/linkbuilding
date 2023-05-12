<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class XSS {

    public function handle($request, Closure $next) {
        $input = $request->all();
        if (isset($_POST)){
            foreach($_POST as $key => $value){
                if(is_string($value)) {
                    $_POST[$key] = strip_tags($value, '<html><body><b><br><em><strong><hr><i><li><ol><p><s><small><span><table><tr><td><u><ul><img>');
                }
            }
        }
        array_walk_recursive($input, function(&$input) {
            $input = strip_tags($input,'<html><body><b><br><em><strong><hr><i><li><ol><p><s><small><span><table><tr><td><u><ul><img>');
        });

        $request->merge($input);

        return $next($request);
    }

}

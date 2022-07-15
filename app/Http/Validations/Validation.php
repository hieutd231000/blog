<?php

namespace App\Http\Validations;

class Validation
{
    /**
     * Validation news
     *
     * @param $request
     * @return void
     */
    public static function newsValidation($request) {
        $request->validate([
            'title' =>'required',
            'description' => 'required',
            'detail' => 'required',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            'state' => 'required',
        ]);
    }

    /**
     * Validation update nes
     *
     * @param $request
     * @return void
     */
    public static function newsUpdateValidation($request) {
        $request->validate([
            'description' => 'required',
            'detail' => 'required',
        ]);
    }
}

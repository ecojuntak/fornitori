<?php

namespace App\Http\Controllers;

trait ImageUtility {

    function storeImages($images) {
        $imageNames = [];

        if(is_iterable($images)) {
            foreach($images as $image) {
                $imageName = time() . $image->getClientOriginalName();
                $destinationPath = public_path('/images/');
                $image->move($destinationPath, $imageName);
                array_push($imageNames, $imageName);
            }

            return $imageNames;
        } else {
            $imageName = time() . $images->getClientOriginalName();
            $destinationPath = public_path('/images/');
            $images->move($destinationPath, $imageName);
            array_push($imageNames, $imageName);
        }

        return $imageNames;
    }
}

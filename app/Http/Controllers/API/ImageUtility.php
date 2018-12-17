<?php

namespace App\Http\Controllers;

trait ImageUtility {

    function storeMultipleImages($images, $folderName) {
        $imageNames = [];

        foreach($images as $image) {
            $imageName = time() . $image->getClientOriginalName();
            $destinationPath = public_path('/images/' . $folderName);
            $image->move($destinationPath, $imageName);

            array_push($imageNames, $imageName);
        }

        return $imageNames;

        return $imageNames;
    }

    function storeSingleImage($image, $folderName) {
        $imageName = time() . $image->getClientOriginalName();
        $destinationPath = public_path('/images/' . $folderName);
        $image->move($destinationPath, $imageName);

        return $imageName;
    }
}

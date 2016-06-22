<?php

$configFile = __DIR__ . '/../config/index.json';
$sourceURL = 'https://raw.githubusercontent.com/iamcal/emoji-data/master/emoji_pretty.json';

$emojiData = json_decode(file_get_contents($sourceURL));

$resultData = [];

foreach ($emojiData as $emoji) {
    if($emoji->has_img_twitter === true) {
        $name = $emoji->short_name;
        $aliases = [];
        // We need to remove the short_name, which is duplicate in this data source
        foreach ($emoji->short_names as $potentialAlias) {
            if($potentialAlias !== $name){
                $aliases[] = str_replace($potentialAlias);
            }
        }
        $resultData[] = array(
            'unicode' =>  $emoji->unified,
            'name' => str_replace('+','plus_',str_replace('-', '_', $name)),
            'description' => strtolower($emoji->name),
            'aliases' => $aliases,
        );
        /*
        if($emoji->skin_variations){
            foreach ($emoji->skin_variations as $key => $skinVariation) {
                $resultData[] = array(
                    'unicode' =>  $skinVariation->unified,
                    'name' => $key,
                    'description' => strtolower($emoji->name),
                    'aliases' => [],
                );
            }
        }
        */
    }
}

$fp = fopen($configFile, 'w');
fwrite($fp, json_encode($resultData, JSON_PRETTY_PRINT));
fclose($fp);

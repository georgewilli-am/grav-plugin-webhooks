<?php

class util
{

    public static function post_request($url, $data)
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }

    public static function build_request($hook, $objects){
      $VAR_REPLACE = array('%PAGE_TITLE%'  => '->title',
                           '%PAGE_SUMMARY' => '->summary',
                           '%PAGE_URL%'    => '->url',
                           '%PAGE_AUTHOR%' => '->header->author',
                           '%PAGE_SLUG%'   => '->slug');

    }
}

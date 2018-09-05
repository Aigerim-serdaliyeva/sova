<?php

function rudr_instagram_api_curl_connect( $api_url ){
    $connection_c = curl_init(); // initializing
    curl_setopt( $connection_c, CURLOPT_URL, $api_url ); // API URL to connect
    curl_setopt( $connection_c, CURLOPT_RETURNTRANSFER, 1 ); // return the result, do not print
    curl_setopt( $connection_c, CURLOPT_TIMEOUT, 20 );
    $json_return = curl_exec( $connection_c ); // connect and get json data
    curl_close( $connection_c ); // close connection
    return json_decode( $json_return ); // decode and return
}

// $user_search is an array of objects of all found users
// we need only the object of the most relevant user - $user_search->data[0]
// $user_search->data[0]->id - User ID
// $user_search->data[0]->first_name - User First name
// $user_search->data[0]->last_name - User Last name
// $user_search->data[0]->profile_picture - User Profile Picture URL
// $user_search->data[0]->username - Username


// if you want to display everything the function returns
function getInstagramPhotos() {
    $access_token = '6276296079.3a187f4.cf4094f9dca1463baf68cbb0867eb3ef';
    $user_photos = rudr_instagram_api_curl_connect("https://api.instagram.com/v1/users/self/media/recent/?access_token=" . $access_token);

    $html = '';
    foreach ($user_photos->data as $post) {
      $body = '<img src="' . $post->images->low_resolution->url . '" />';

      if (property_exists($post, 'videos')) {
        $body = '<video controls poster="' . $post->images->low_resolution->url . '"><source src="' . $post->videos->low_resolution->url . '" type="video/mp4"></video>';
      }

      $html .= '<div class="insta">
          <div class="insta__head">
              <a class="insta__logo" href="https://instagram.com/sova_studio_production" data-ios-link="user?username=sova_studio_production" data-log-event="profilePhotoClick" target="_blank">
                  <img src="' . $post->user->profile_picture . '" />
              </a>
              <a class="insta__name" href="https://instagram.com/sova_studio_production" data-ios-link="user?username=sova_studio_production" data-log-event="profilePhotoClick" target="_blank">
                  ' . $post->user->username . '
              </a>
              <a class="insta__btn" href="https://instagram.com/sova_studio_production" data-ios-link="user?username=sova_studio_production" data-log-event="profilePhotoClick" target="_blank">
                  Посмотреть профиль
              </a>
          </div>
          <div class="insta__body">
            ' . $body . '
          </div>
          <div class="insta__foot">
              <div class="insta__likes">
              <i class="fa fa-heart"></i>
              ' . $post->likes->count .' отметок "Нравится"
              </div>
              <div class="insta__caption">
              ' . mb_substr($post->caption->text, 0, 300) . '
              </div>
          </div>
      </div>';
    }
    return $html;
}

echo getInstagramPhotos();
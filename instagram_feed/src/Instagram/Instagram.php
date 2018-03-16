<?php
namespace Concrete\Package\InstagramFeed\Src\Instagram;

use Concrete\Package\InstagramFeed\Src\Instagram\Endpoints;
use Concrete\Package\InstagramFeed\Src\Instagram\Unirest\Request;
use Concrete\Package\InstagramFeed\Src\Instagram\Model\Account;
use Concrete\Package\InstagramFeed\Src\Instagram\Model\Media;



class Instagram
{

  const HTTP_NOT_FOUND = 404;
  const HTTP_OK = 200;
  const MAX_COMMENTS_PER_REQUEST = 300;
  const MAX_LIKES_PER_REQUEST = 300;
  const PAGING_TIME_LIMIT_SEC = 1800; // 30 mins time limit on operations that require multiple requests
  const PAGING_DELAY_MINIMUM_MICROSEC = 1000000; // 1 sec min delay to simulate browser
  const PAGING_DELAY_MAXIMUM_MICROSEC = 3000000; // 3 sec max delay to simulate browser

  private $userAgent = null;


  /**
   * @param \stdClass|string $rawError
   *
   * @return string
   */
  private static function getErrorBody($rawError)
  {
      if (is_string($rawError)) {
          return $rawError;
      }
      if (is_object($rawError)) {
          $str = '';
          foreach ($rawError as $key => $value) {
              $str .= ' ' . $key . ' => ' . $value . ';';
          }
          return $str;
      } else {
          return 'Unknown body format';
      }

  }

  /**
   * @param $userAgent
   *
   * @return string
   */
  public function setUserAgent($userAgent)
  {
      return $this->userAgent = $userAgent;
  }

  /**
   * @param $userAgent
   *
   * @return null
   */
  public function resetUserAgent($userAgent)
  {
      return $this->userAgent = null;
  }

  /**
   *
   * @return string
   */
  public function getUserAgent()
  {
      return $this->userAgent;
  }



  /**
   * @param $session
   *
   * @return array
   */
  private function generateHeaders($session)
  {
      $headers = [];
      if ($session) {
          $cookies = '';
          foreach ($session as $key => $value) {
              $cookies .= "$key=$value; ";
          }
          $headers = [
              'cookie'      => $cookies,
              'referer'     => Endpoints::BASE_URL . '/',
              'x-csrftoken' => $session['csrftoken'],
          ];
      }

      if($this->getUserAgent())
      {
          $headers['user-agent'] = $this->getUserAgent();
      }

      return $headers;
  }


  /**
   * @param string $username
   * @param int $count
   * @param string $maxId
   *
   * @return Media[]
   * @throws InstagramException
   */
  public function getMedias($username, $count = 20, $maxId = '')
  {
    $account = $this->getAccount($username);
    $index = 0;
    $medias = [];
    $isMoreAvailable = true;
    while ($index < $count && $isMoreAvailable) {
      $response = Request::get(Endpoints::getAccountMediasJsonLink($account->getId(), $maxId), $this->generateHeaders($this->userSession));
      if (static::HTTP_OK !== $response->code) {
        throw new InstagramException('Response code is ' . $response->code . '. Body: ' . static::getErrorBody($response->body) . ' Something went wrong. Please report issue.');
      }
      $arr = json_decode($response->raw_body, true, 512, JSON_BIGINT_AS_STRING);
      if (!is_array($arr)) {
        throw new InstagramException('Response code is ' . $response->code . '. Body: ' . static::getErrorBody($response->body) . ' Something went wrong. Please report issue.');
      }
      $nodes = $arr['data']['user']['edge_owner_to_timeline_media']['edges'];
      // fix - count takes longer/has more overhead
      if (!isset($nodes) || empty($nodes)) {
        return [];
      }
      foreach ($nodes as $mediaArray) {
        if ($index === $count) {
          return $medias;
        }
        $medias[] = Media::create($mediaArray['node']);
        $index++;
      }
      if (empty($nodes) || !isset($nodes)) {
        return $medias;
      }
      $maxId = $arr['data']['user']['edge_owner_to_timeline_media']['page_info']['end_cursor'];
      $isMoreAvailable = $arr['data']['user']['edge_owner_to_timeline_media']['page_info']['has_next_page'];
    }
    return $medias;
  }

  /**
   * @param string $username
   *
   * @return Account
   * @throws InstagramException
   * @throws InstagramNotFoundException
   */
  public function getAccount($username)
  {
      $response = Request::get(Endpoints::getAccountJsonLink($username), $this->generateHeaders($this->userSession));
      if (static::HTTP_NOT_FOUND === $response->code) {
          throw new InstagramNotFoundException('Account with given username does not exist.');
      }
      if (static::HTTP_OK !== $response->code) {
          throw new InstagramException('Response code is ' . $response->code . '. Body: ' . static::getErrorBody($response->body) . ' Something went wrong. Please report issue.');
      }

      $userArray = json_decode($response->raw_body, true, 512, JSON_BIGINT_AS_STRING);
      if (!isset($userArray['graphql']['user'])) {
          throw new InstagramException('Account with this username does not exist');
      }
      return Account::create($userArray['graphql']['user']);
  }


}

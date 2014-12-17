<?php
# node_url class definition file
class node_service_twitter extends node_service {
  
	public function fetchentitytweets() {

		global $uos;
	
		$twitter = $this->gettwitteroauth();
		
		$twitter->ssl_verifypeer = true;
		

		//$tweets = $twitter->get('https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=craigbuckler&count=10');
		//$tweets = $twitter->get('https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=x_a_i_o&count=10');
		//$tweets = $twitter->get('statuses/user_timeline', array('screen_name' => 'x_a_i_o', 'exclude_replies' => 'true', 'include_rts' => 'false', 'count' => 10));
		$tweets = $twitter->get('statuses/user_timeline', array('include_entities'=>FALSE));

		//$this->addproperty('testi','field_text',array('value'=>print_r($tweets,TRUE)));

		foreach($tweets as &$tweet) {
			$tweetnode = new node_message_tweet(array(
				'guid'=>$this->guid->value.'['.$tweet->id.']',
				'body'=> $tweet->text,
				'messageid'=> $tweet->id,
				'title'=> '@'.$tweet->user->name . ' ('.$tweet->id .')',
				'created'=> $tweet->created_at,
				'modified'=> $tweet->created_at 
			));
			$tweetnode->addproperty('imageurl','field_text',array('value'=>$tweet->user->name));
			$this->children[] = $tweetnode;
		}	
	}
	
	
	/* filter objects */
	/* Tags / Relationships / Child of */
	/* Field names */
	
	public function children($filter) {
	
		$children = array();
	
		$twitter = $this->gettwitteroauth();
		
		$twitter->ssl_verifypeer = true;
		
		$twittersearch = implode('+OR+',$filter->tags);
		
		if (isset($filter->starttime)) {
			$twittersearch .= '+since:'.date('Y-d-m',$filterobj->starttime);
		}
		//return 'xxxx';
		//return $twittersearch;
		
		$twittersearch = urlencode($twittersearch);
		$tweets = $twitter->get('search/tweets', array('q' => $twittersearch,'include_entities'=>FALSE));
		//return $tweets;
		//return $tweets;
		
		foreach($tweets->statuses as &$tweet) {	
			
			if ($filter->starttime && strtotime($tweet->created_at)<($filter->starttime)) continue;
			$tweetnode = new node_message_tweet(array(
				'body'=> $tweet->text,
				'messageid'=> $tweet->id,
				'title'=> '@'.$tweet->user->name . ' ('.$tweet->id .')',
				'created'=> $tweet->created_at,
				'modified'=> $tweet->created_at,
				'sourceid'=>($this->guid.'['.$tweet->id.']')
			));	
			$tweetnode->addproperty('imageurl','field_text',array('value'=>$tweet->user->profile_image_url));
			$children[] = $tweetnode;	
		}
		return $children;
	}
	
	private function gettwitteroauth() {
		includeLibrary('twitteroauth');
		return new TwitterOAuth($this->consumerkey->value, $this->consumersecret->value, $this->accesstoken->value, $this->accesstokensecret->value);
	}
	
	public function fetchchildren() {
		$this->fetchentitytweets();
	}

} 

/*
   [0] => stdClass Object
        (
            [created_at] => Fri Nov 07 13:42:43 +0000 2014
            [id] => 530716982904360961
            [id_str] => 530716982904360961
            [text] => Browser Trends November 2014: #IE Drops Below 20% - http://t.co/sS63gghy65 - Chrome jumps 2% by being not quite as problematic as others!
            [source] => <a href="http://www.hootsuite.com" rel="nofollow">Hootsuite</a>
            [truncated] => 
            [in_reply_to_status_id] => 
            [in_reply_to_status_id_str] => 
            [in_reply_to_user_id] => 
            [in_reply_to_user_id_str] => 
            [in_reply_to_screen_name] => 
            [user] => stdClass Object
                (
                    [id] => 18670151
                    [id_str] => 18670151
                    [name] => Craig Buckler
                    [screen_name] => craigbuckler
                    [location] => Exmouth, Devon, UK
                    [profile_location] => 
                    [description] => SitePoint tech blogger. Freelance UK IT consultant specialising in HTML5 webby stuff. Doesn't understand Twitter's appeal but still uses it. The hypocrite.
                    [url] => http://t.co/QRF2W5wnZU
                    [entities] => stdClass Object
                        (
                            [url] => stdClass Object
                                (
                                    [urls] => Array
                                        (
                                            [0] => stdClass Object
                                                (
                                                    [url] => http://t.co/QRF2W5wnZU
                                                    [expanded_url] => http://optimalworks.net/
                                                    [display_url] => optimalworks.net
                                                    [indices] => Array
                                                        (
                                                            [0] => 0
                                                            [1] => 22
                                                        )

                                                )

                                        )

                                )

                            [description] => stdClass Object
                                (
                                    [urls] => Array
                                        (
                                        )

                                )

                        )

                    [protected] => 
                    [followers_count] => 1205
                    [friends_count] => 174
                    [listed_count] => 100
                    [created_at] => Tue Jan 06 11:06:20 +0000 2009
                    [favourites_count] => 5
                    [utc_offset] => 0
                    [time_zone] => London
                    [geo_enabled] => 
                    [verified] => 
                    [statuses_count] => 3650
                    [lang] => en
                    [contributors_enabled] => 
                    [is_translator] => 
                    [is_translation_enabled] => 
                    [profile_background_color] => C0DEED
                    [profile_background_image_url] => http://abs.twimg.com/images/themes/theme1/bg.png
                    [profile_background_image_url_https] => https://abs.twimg.com/images/themes/theme1/bg.png
                    [profile_background_tile] => 
                    [profile_image_url] => http://pbs.twimg.com/profile_images/2441718490/omrhb47p1j0ysgpr1wxd_normal.jpeg
                    [profile_image_url_https] => https://pbs.twimg.com/profile_images/2441718490/omrhb47p1j0ysgpr1wxd_normal.jpeg
                    [profile_link_color] => 0084B4
                    [profile_sidebar_border_color] => C0DEED
                    [profile_sidebar_fill_color] => DDEEF6
                    [profile_text_color] => 333333
                    [profile_use_background_image] => 1
                    [default_profile] => 1
                    [default_profile_image] => 
                    [following] => 
                    [follow_request_sent] => 
                    [notifications] => 
                )

            [geo] => 
            [coordinates] => 
            [place] => 
            [contributors] => 
            [retweet_count] => 0
            [favorite_count] => 0
            [entities] => stdClass Object
                (
                    [hashtags] => Array
                        (
                            [0] => stdClass Object
                                (
                                    [text] => IE
                                    [indices] => Array
                                        (
                                            [0] => 30
                                            [1] => 33
                                        )

                                )

                        )

                    [symbols] => Array
                        (
                        )

                    [user_mentions] => Array
                        (
                        )

                    [urls] => Array
                        (
                            [0] => stdClass Object
                                (
                                    [url] => http://t.co/sS63gghy65
                                    [expanded_url] => http://ow.ly/DXT4N
                                    [display_url] => ow.ly/DXT4N
                                    [indices] => Array
                                        (
                                            [0] => 52
                                            [1] => 74
                                        )

                                )

                        )

                )

            [favorited] => 
            [retweeted] => 
            [possibly_sensitive] => 
            [lang] => en
        )
*/


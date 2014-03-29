<?php
# node_url class definition file
class node_service_twitter extends node_service {
  
	public function fetchentitytweets() {

		global $uos;
	
		$twitter = $this->gettwitteroauth();
				
		$tweets = $twitter->get('https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=x_a_i_o&count=10');
		foreach($tweets as &$tweet) {
			$this->children[] = new node_message_tweet($tweet);
		}	
	}
	
	private function gettwitteroauth() {
		includeLibrary('twitteroauth');
		return new TwitterOAuth($this->consumerkey->value, $this->consumersecret->value, $this->accesstoken->value, $this->accesstokensecret->value);
	}

} 
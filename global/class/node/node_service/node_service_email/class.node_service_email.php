<?php
# node_url class definition file
class node_service_email extends node_service {
  
	public function fetchchildren() {

		global $uos;
		
		//$hostname = '{imap.gmail.com:993/imap/ssl}INBOX';
		//$username = 'davidwalshblog@gmail.com';
		//$password = 'davidwalsh';

	
		if (!function_exists('imap_open')) {
			die('PHP IMAP not setup');
		}
 
		/* try to connect */
		try {
			$inbox = @imap_open('{'.$this->hostname->value.':993/imap/ssl}INBOX',$this->username->value,$this->password->value);
		} catch(Exception $e) {
			$inbox = FALSE;
		}
		// or die('Cannot connect to Gmail: ' . imap_last_error());
		$this->addproperty('imap','field_text',array('value'=>print_r($inbox,TRUE)));
		$this->addproperty('testi','field_text',array('value'=>print_r(imap_last_error(),TRUE)));
		//return TRUE;		
		/* grab emails */
		$emails = imap_search($inbox,'ALL');
		
		/* if emails are returned, cycle through each... */
		if($emails) {
		
			/* begin output var */
			$output = '';
			
			/* put the newest emails on top */
			rsort($emails);
			
			/* for every email... */
			foreach($emails as $email_number) {
				
				/* get information specific to this email */
				$overview = imap_fetch_overview($inbox,$email_number,0);
				$htmlmessage = quoted_printable_decode(imap_fetchbody($inbox,$email_number,2));
				$plainmessage = imap_fetchbody($inbox,$email_number, 1);
				//$createddate = strtotime(trim($overview['date']));
				//$createddate = $overview[0]->date;
				$emailnode = new node_message_email(array(
					'guid'=>$this->guid->value.'['.$email_number.']',
					'created' => $overview[0]->date,
					'modified' => $overview[0]->date,
					'messageid' => $email_number,
				  'title' => $overview[0]->subject . $email_number,
				  'body' => $plainmessage,
				  'bodyhtml' => $htmlmessage,
				));
				//$emailnode = new node();
				//$emailnode->title->value = print_r(array_keys($emailnode->properties),TRUE);
				//$emailnode->created->setvalue($overview['date']);
				$this->children[] = $emailnode;
				/*
				// output the email header information 
				$output.= '<div class="toggler '.($overview[0]->seen ? 'read' : 'unread').'">';
				$output.= '<span class="subject">'.$overview[0]->subject.'</span> ';
				$output.= '<span class="from">'.$overview[0]->from.'</span>';
				$output.= '<span class="date">on '.$overview[0]->date.'</span>';
				$output.= '</div>';
				
				// output the email body
				$output.= '<div class="body">'.$message.'</div>';
				*/
			}
	
		}
	}

	
} 
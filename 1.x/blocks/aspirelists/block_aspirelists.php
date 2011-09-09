<?php
// Copyright (c) Talis Education Limited, 2010
// Released under the LGPL Licence - http://www.gnu.org/licenses/lgpl.html. Anyone is free to change or redistribute this code.

class block_aspirelists extends block_base {
  function init() {
    $this->title   = get_string('blockname', 'block_aspirelists');
    $this->version = 2010073002;
  }

  function get_content() {
	global $CFG;
	global $COURSE;

    if ($this->content !== NULL) {
      return $this->content;
    }

	if (empty($CFG->block_aspirelists_targetAspire))
	{
		$this->content->text = "Talis Aspire base URL not configured. Contact the system administrator.";
		return $this->content;
	}

	$site = $CFG->block_aspirelists_targetAspire;
	
	if (!empty($CFG->block_aspirelists_targetKG))
	{
		$targetKG = $CFG->block_aspirelists_targetKG;
	}
	else
	{
		$targetKG = "modules"; // default to modules
	}

    $this->content =  new stdClass;
	if ($COURSE->idnumber)
	{
		// get the code from the global course object, lowercasing it in the process
		$code = strtolower($COURSE->idnumber);  
		$url = "$site/$targetKG/$code/lists.json"; // build the target URL of the JSON data we'll be requesting from Aspire
		// using php curl, we'll now request the JSON data from Aspire
		$ch = curl_init();
		$options = array(
		    CURLOPT_URL            => $url, // tell curl the URL
		    CURLOPT_HEADER         => false,
		    CURLOPT_RETURNTRANSFER => true,
		    CURLOPT_CONNECTTIMEOUT => 20,
		    CURLOPT_HTTP_VERSION      => CURL_HTTP_VERSION_1_1
		);
		curl_setopt_array($ch, $options);
		$response = curl_exec($ch); // execute the request and get a response

		$output = '';
		if ($response) // if we get a valid response from curl...
		{
			$data = json_decode($response,true); // decode the returned JSON data
			if(isset($data["$site/$targetKG/$code"]) && isset($data["$site/$targetKG/$code"]['http://purl.org/vocab/resourcelist/schema#usesList'])) // if there are any lists...
			{
				$lists = array();
				foreach ($data["$site/$targetKG/$code"]['http://purl.org/vocab/resourcelist/schema#usesList'] as $usesList) // for each list this module uses...
				{
					$list = array();
					$list["url"] = $usesList["value"]; // extract the list URL
					$list["name"] = $data[$list["url"]]['http://rdfs.org/sioc/spec/name'][0]['value']; // extract the list name

					// let's try and get a last updated date
					if (isset($data[$list["url"]]['http://purl.org/vocab/resourcelist/schema#lastUpdated'])) // if there is a last updated date...
					{
						// set up the timezone 
						date_default_timezone_set('Europe/London');

						// ..and extract the date in a friendly, human readable format...
						$list['lastUpdatedDate'] = date('l j F Y',
						    strtotime($data[$list["url"]]['http://purl.org/vocab/resourcelist/schema#lastUpdated'][0]['value'])); 
					}

					// now let's count the number of items
					$itemCount = 0; 
					if (isset($data[$list["url"]]['http://purl.org/vocab/resourcelist/schema#contains'])) // if the list contains anything...
					{
						foreach ($data[$list["url"]]['http://purl.org/vocab/resourcelist/schema#contains'] as $things) // loop through the list of things the list contains...
						{
							if (preg_match('/\/items\//',$things['value'])) // if the thing is an item, incrememt the item count (lists can contain sections, too)
							{
								$itemCount++; 
							}
						}
					}
					$list['count'] = $itemCount;
					array_push($lists,$list);
				}
				usort($lists,'sortByName');
				foreach ($lists as $list)
				{
					$itemNoun = ($list['count'] == 1) ? "item" : "items"; // get a friendly, human readable noun for the items

					// finally, we're ready to output information to the browser
					$output .= "<p><a href='".$list['url']."'>".$list['name']."</a>";
					if ($list['count'] > 0) // add the item count if there are any...
					{
						$output .= " (".$list['count']." $itemNoun)";
					}
					if (isset($list["lastUpdatedDate"]))
					{
						$output .= ', last updated '.contextualTime(strtotime($list["lastUpdatedDate"])); 
					}
					$output .= "</p>\n";
				}
			}
		}
		if ($output=='')
		{
		    $this->content->text   = "<p>No resource lists found for $COURSE->fullname.</p>";
		}
		else
		{
		    $this->content->text   = $output;
		}
	}

    return $this->content;
  }

  function has_config() {
    return true;
  }

  function applicable_formats() {
    return array('course-view' => true);
  }



}

function contextualTime($small_ts, $large_ts=false) {
  if(!$large_ts) $large_ts = time();
  $n = $large_ts - $small_ts;
  if($n <= 1) return 'less than 1 second ago';
  if($n < (60)) return $n . ' seconds ago';
  if($n < (60*60)) { $minutes = round($n/60); return 'about ' . $minutes . ' minute' . ($minutes > 1 ? 's' : '') . ' ago'; }
  if($n < (60*60*16)) { $hours = round($n/(60*60)); return 'about ' . $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago'; }
  if($n < (time() - strtotime('yesterday'))) return 'yesterday';
  if($n < (60*60*24)) { $hours = round($n/(60*60)); return 'about ' . $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago'; }
  if($n < (60*60*24*6.5)) return 'about ' . round($n/(60*60*24)) . ' days ago';
  if($n < (time() - strtotime('last week'))) return 'last week';
  if(round($n/(60*60*24*7))  == 1) return 'about a week ago';
  if($n < (60*60*24*7*3.5)) return 'about ' . round($n/(60*60*24*7)) . ' weeks ago';
  if($n < (time() - strtotime('last month'))) return 'last month';
  if(round($n/(60*60*24*7*4))  == 1) return 'about a month ago';
  if($n < (60*60*24*7*4*11.5)) return 'about ' . round($n/(60*60*24*7*4)) . ' months ago';
  if($n < (time() - strtotime('last year'))) return 'last year';
  if(round($n/(60*60*24*7*52)) == 1) return 'about a year ago';
  if($n >= (60*60*24*7*4*12)) return 'about ' . round($n/(60*60*24*7*52)) . ' years ago'; 
  return false;
}

function sortByName($a,$b)
{
    return strcmp($a["name"], $b["name"]);
}
?>
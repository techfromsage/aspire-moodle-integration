<?php
// Copyright (c) Talis Education Limited, 2013
// Released under the LGPL Licence - http://www.gnu.org/licenses/lgpl.html. Anyone is free to change or redistribute this code.

class block_aspirelists extends block_base {
  function init() {
    $this->title   = get_config('aspirelists', 'blockTitle');
  }

  function get_content() {
	global $CFG;
	global $COURSE;

    if ($this->content !== NULL) {
      return $this->content;
    }

	$site = get_config('aspirelists', 'targetAspire');
    $httpsAlias = get_config('aspirelists', 'targetAspireAlias');

	if (empty($site))
	{
		$this->content->text = get_string('no_base_url_configured', 'block_aspirelists');
		return $this->content;
	}

	$targetKG = get_config('aspirelists', 'targetKG');
	if (empty($targetKG))
	{
		$targetKG = "modules"; // default to modules
	}

    $hrefTarget = get_config('aspirelists', 'openNewWindow');
    $target ='_self';
    if($hrefTarget == 1){
        $target = '_blank';
    }

    $this->content =  new stdClass;
	if ($COURSE->idnumber)
	{
		// get the code from the global course object
		$codeGlobal = $COURSE->idnumber;

        $moduleCodeRegEx = '/'.get_config('aspirelists', 'moduleCodeRegex').'/';
        $timePeriodRegEx = '/'.get_config('aspirelists', 'timePeriodRegex').'/';

        $urlModuleCode = '';
        $urlTimePeriod = '';

        // decide how to split up the moodle course id.
        if($moduleCodeRegEx != '//')
        {
            $results = array();
            if (preg_match($moduleCodeRegEx, $codeGlobal, $results) == 1) // we have a match
            {
                $urlModuleCode = strtolower($results[1]); // make sure is lowercase fr URL.
            }
            else
            {
                // we'll see if something matches anyway?
                $urlModuleCode = strtolower($codeGlobal);
            }
        }
        if( $timePeriodRegEx != '//')
        {
            $results = array();
            if (preg_match($timePeriodRegEx, $codeGlobal, $results) == 1) // we have a match
            {
                $mapping = json_decode(get_config('aspirelists', 'timePeriodMapping'),true);
                if($mapping != null)
                {
                    $urlTimePeriod = strtolower($mapping[$results[1]]); // make sure is lowercase for URL.
                }
                else
                {
                    // there is no mapping so just use the result
                    $urlTimePeriod = strtolower($results[1]);
                }
            }
        }

        // build the target URL of the JSON data we'll be requesting from Aspire

        if(!empty($httpsAlias))
        {
            $baseUrl = $httpsAlias;
        }
        else
        {
            $baseUrl = $site;
        }

        if($urlTimePeriod != ''){
            $url = "{$baseUrl}/{$targetKG}/{$urlModuleCode}/lists/{$urlTimePeriod}.json";
        }
        else
        {
            $url = "{$baseUrl}/{$targetKG}/{$urlModuleCode}/lists.json";
        }
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
            // JSON data will be using the non https alias.
			if(isset($data["$site/$targetKG/$urlModuleCode"]) && isset($data["$site/$targetKG/$urlModuleCode"]['http://purl.org/vocab/resourcelist/schema#usesList'])) // if there are any lists...
			{
				$lists = array();
				foreach ($data["$site/$targetKG/$urlModuleCode"]['http://purl.org/vocab/resourcelist/schema#usesList'] as $usesList) // for each list this module uses...
				{
					$list = array();
					$list["url"] = clean_param($usesList["value"], PARAM_URL); // extract the list URL
					$list["name"] = clean_param($data[$list["url"]]['http://rdfs.org/sioc/spec/name'][0]['value'], PARAM_TEXT); // extract the list name

					// let's try and get a last updated date
					if (isset($data[$list["url"]]['http://purl.org/vocab/resourcelist/schema#lastUpdated'])) // if there is a last updated date...
					{
						// set up the timezone 
						date_default_timezone_set('Europe/London');

						// ..and extract the date in a friendly, human readable format...
						$list['lastUpdatedDate'] = date('l j F Y',
						    strtotime(clean_param($data[$list["url"]]['http://purl.org/vocab/resourcelist/schema#lastUpdated'][0]['value'], PARAM_TEXT)));
					}

					// now let's count the number of items
					$itemCount = 0; 
					if (isset($data[$list["url"]]['http://purl.org/vocab/resourcelist/schema#contains'])) // if the list contains anything...
					{
						foreach ($data[$list["url"]]['http://purl.org/vocab/resourcelist/schema#contains'] as $things) // loop through the list of things the list contains...
						{
							if (preg_match('/\/items\//',clean_param($things['value'], PARAM_URL))) // if the thing is an item, increment the item count (lists can contain sections, too)
							{
								$itemCount++; 
							}
						}
					}
					$list['count'] = $itemCount;
					array_push($lists,$list);
				}
				usort($lists,array($this,'sortByName'));
				foreach ($lists as $list)
				{
					$itemNoun = ($list['count'] == 1) ? get_string("item", 'block_aspirelists') : get_string("items", 'block_aspirelists'); // get a friendly, human readable noun for the items

					// finally, we're ready to output information to the browser

                    // item count display
                    $itemCountHtml = '';
                    if ($list['count'] > 0) // add the item count if there are any
                    {
                        $itemCountHtml = html_writer::tag('span', " ({$list['count']} {$itemNoun})" ,array('class'=>'aspirelists-item-count'));
                    }

                    // last update display
                    $lastUpdatedHtml = '';
                    if (isset($list["lastUpdatedDate"]))
                    {
                        $lastUpdatedHtml = html_writer::tag('span',', '.get_string('lastUpdated','block_aspirelists').' '.$this->contextualTime(strtotime($list["lastUpdatedDate"])) , array('class'=>'aspirelists-last-updated'));
                    }

                    // put it all together
                    $output .= html_writer::tag('p',
                        html_writer::tag('a', $list['name'] , array('href' => $list['url'], 'target' => $target)) . html_writer::empty_tag('br') . $itemCountHtml . $lastUpdatedHtml );
				}
			}
		}
		if ($output=='')
		{
		    $this->content->text   = html_writer::tag('p', get_config('aspirelists', 'noResourceListsMessage'));
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
    return array(
        'course-view' => true,
        'site' => true
    );
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

}

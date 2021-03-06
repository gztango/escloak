<?php
/**
 * Created by PhpStorm.
 * User: Gztango
 * Date: 2017/11/6
 * Time: 21:39
 */

namespace Gztango;

class Escloak
{
	
	private $sing_key;
    private $campaign_id;

    public function start($sing_key,$campaign_id,$debug=false)
    {
        $from_server = $_GET['from_es'];
        if($from_server == 'v1.0'){
            return true;
        }
    	$this->sing_key = $sing_key;
    	$this->campaign_id = $campaign_id;

		$ta = new ESLoader($sing_key,$campaign_id,$debug);
		if ($ta->suppress_response()) {
//		    echo 'hello';
		    exit;
		}

		$response = $ta->get_response();

//		$visitor = $ta->get_visitor();
        $response['url'] = $ta->add_querystring_var($response['url']);
		$func = new CloakFunc();
		switch ($response['action']) {
		    case 'header_redirect':
		        print $func->header_redirect($response['url'],$response);
		        exit;
		    case 'iframe':
		        print $func->load_fullscreen_iframe($response['url']);
		        exit;
		    case 'paste_html':
		        print $func->paste_html($response['output_html']);
		        exit;
		    case 'load_hybrid_page':
		        $ta->load_hybrid_page();
		        break;
		}
    }
}
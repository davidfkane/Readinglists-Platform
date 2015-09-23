<?
class Amazon_model extends CI_Model {

    function Amazon_model()
    {
        parent::__construct();
		# $this->load->database();
    }
    
    function search_amazon($keywords, $item_page)
    {   
    	//Enter your Amazon Services Key Id as the value for $key_id
    	//If you need a key id, sign-up here: http://aws.amazon.com/associates/
		define('KEYID','AKIAJ5PQ5ZIASRJS5ERQ');

		//Associate Tag is optional.
		define('AssocTag','YourAssociateTagHere');

		//This tells Amazon to only search through books.  Other values are possible
		//aswell, such as videos
		$search_index ='Books';
		
		//Submit and process Amazon Query
		$request="http://ecs.amazonaws.com/onca/xml?Service=AWSECommerceService&AWSAccessKeyId=".KEYID."&AssociateTag=".AssocTag."&Version=2006-09-11&Operation=ItemSearch&ResponseGroup=Medium,Offers";
		$request.="&SearchIndex=$search_index&Keywords=$keywords&ItemPage=$item_page";

		$session = curl_init($request);
		curl_setopt($session, CURLOPT_HEADER, false);
		curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($session);
		curl_close($session); 
		$parsed_xml = simplexml_load_string($response);
		
		//Return parsed Amazon data
		return $parsed_xml;				
    }

}
?>
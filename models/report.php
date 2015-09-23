<?php
class Report extends CI_Model {
	/**
	BOOKS:  This is where all the action happens, edit, update and delete...
	
			|	 	ADD	 	| 	 EDIT 	|	EDIT/LIB| 	 DELETE 	 |
-----------------------------------------------------------------------
BOOK 		|		YES		|	yes 	|	no		|		N/A		 |
-----------------------------------------------------------------------
BOOK_MODULE |		YES		|			|			|				 |
-----------------------------------------------------------------------

Note on recording the creation of new items.  This should take the form of a 'create date' and 'create user' in both the BOOK and BOOK_MODULE tables.

This is necessary because it makes the recording of the audit trail less complex. It makes it easier to record new 

*/
    function Report(){
        // Call the Model constructor
        parent::__construct();
		$this->load->database();
    }
	
	function genericreportchart(){
		$query = $params;
		$return = array();
		$return['title'] = "Title of Chart";
		$return['xvalues'] = $xvalues;
		$return['yvalues'] = $yvalues;
	}
	
	function getallclicks(){
		$q = 'select `id`, `username`, `usertype`,  `modulename`, `list`, `books`.`title`, `list-item`, `clickthroughs`.`type`, `source`, `clickthroughs`.`url`, ';
		$q .= '`catalogue`, `essential`, `timestamp`, ';
		$q .= 'INET_NTOA(ipaddress) as ipaddress, `platform`, ';
		$q .= '`browser`, `version`, `win32`, `ismobiledevice` as ismob,  `modules`.`teachers`  ';
		$q .= 'from clickthroughs, modules, books ';
		$q .= 'where `modules`.`mid` = `clickthroughs`.`list` ';
		$q .= 'and `books`.`bid` = `clickthroughs`.`list-item` ';
		$q .= 'order by timestamp desc;';
		$query = $this->db->query($q);
		return $query->result_array();
	}
	
	function modules_clickthroughs($mid, $daysago = 14){
		// get all modules, names and IDs for user.
		// foreach module get array of clickthroughs
		$q = 'SELECT count(*) as clickthroughs, DATE_FORMAT(timestamp, "%a, %D %b") as day, ';
		$q .= 'DATEDIFF(DATE_FORMAT(NOW() , "%Y-%m-%d"), DATE_FORMAT(timestamp, "%Y-%m-%d")) as daysago ';
		$q .= 'FROM clickthroughs ';
		$q .= 'where list = 6476 ';
		//		$q .= 'where list = '.$mid.' ';
		$q .= 'and timestamp >= DATE_ADD(CURDATE(), INTERVAL -'.$daysago.' DAY) ';
		$q .= 'group by day ';
		$q .= 'order by daysago;';
		
		$query = $this->db->query($q);
		
		return $query->result_array();
	}
	
	function missing(){
		$querystring = '
			select a.bid as BID1, b.dname, a.Title, a.Author, Year, (
				select count(*) 
				from books_modules c
				where c.bid = a.bid
        and c.inactive = 0
			) as mods, (
				select count(*) 
				from books_modules cx, books x
				where cx.bid = a.bid
				and x.bid = cx.bid
				and cx.essential = \'ess\'
        and cx.inactive = 0
			) as essential_in_mods 
			from books a, departments b 
			where a.date_updated is not NULL 
			and (a.libid is NULL or a.libid = \'\')
			and a.Did = b.did
			order by mods desc;
		';		
		$query = $this->db->query($querystring);
		return $query->result_array();

	}

	function books_modules($book_id){
		
		$querystring = '
			select b.inactive, a.Title, b.mid, c.MOODLE_INTERNAL_ID, c.modulename, c.Did, d.dname, b.essential
from books a, books_modules b, modules c, departments d
where a.bid = b.bid
and b.mid = c.mid
and c.Did = d.did 
and a.date_updated is not NULL
and (a.libid is NULL or a.libid = \'\')
and b.inactive = 0
and b.bid = '.$book_id.' order by d.dname, c.modulename asc;
		';		
		$query = $this->db->query($querystring);
		return $query->result_array();

	}
}
?>
